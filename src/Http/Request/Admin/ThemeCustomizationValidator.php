<?php

namespace Takemo101\CmsTool\Http\Request\Admin;

use CmsTool\Session\Csrf\CsrfGuard;
use CmsTool\Theme\Schema\Setting\AbstractInputSetting;
use CmsTool\Theme\Schema\Setting\CheckboxSetting;
use CmsTool\Theme\Schema\Setting\ColorSetting;
use CmsTool\Theme\Schema\Setting\EditorSetting;
use CmsTool\Theme\Schema\Setting\NumberSetting;
use CmsTool\Theme\Schema\Setting\SelectOption;
use CmsTool\Theme\Schema\Setting\SelectSetting;
use CmsTool\Theme\Schema\Setting\TextareaSetting;
use CmsTool\Theme\Schema\Setting\TextSetting;
use CmsTool\Theme\Theme;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ThemeCustomizationValidator
{
    /** @var string[] */
    public const IgnoreFields = [
        '_METHOD',
        CsrfGuard::TokenNameKey,
        CsrfGuard::TokenValueKey,
    ];

    /**
     * constructor
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {
        //
    }

    /**
     * Validate the body
     *
     * @param Theme $theme
     * @param array<string,mixed> $body
     * @return ConstraintViolationListInterface
     */
    public function validate(
        Theme $theme,
        array $body,
    ): ConstraintViolationListInterface {

        return $this->validator->validate(
            $this->filter($body),
            $this->getConstraint($theme),
        );
    }

    /**
     * Filter the body
     *
     * @param array<string,mixed> $body
     * @return array<string,mixed>
     */
    private function filter(array $body): array
    {
        return array_filter(
            $body,
            fn ($value, $key) => in_array($key, self::IgnoreFields) === false,
            ARRAY_FILTER_USE_BOTH,
        );
    }

    /**
     * Get symfony validation constraint
     *
     * @param Theme $theme
     * @return Constraint
     */
    private function getConstraint(Theme $theme): Constraint
    {
        $schema = $theme->meta->schema;

        $constraints = [];

        foreach ($schema->settings as $settings) {

            $settingsConstraints = [];

            foreach ($settings->getInputSettings() as $setting) {
                $settingsConstraints[$setting->id->value()] = $this->createSchemaSettingConstraints($setting);
            }

            $constraints[$settings->id->value()] = new Assert\Collection($settingsConstraints);
        }

        return new Assert\Collection($constraints);
    }

    /**
     * Create schema setting constraints
     *
     * @param AbstractInputSetting $setting
     * @return Constraint[]
     */
    private function createSchemaSettingConstraints(AbstractInputSetting $setting): array
    {
        /** @var Constraint[] */
        $constraints = match (true) {
            $setting instanceof CheckboxSetting => [
                new Assert\Optional(
                    new Assert\Callback(fn (
                        mixed $value,
                        ExecutionContextInterface $context,
                    ) => $this->isBoolenaConvertible($value)
                        ? null
                        : $context->buildViolation('The value must be boolean convertible')
                        ->addViolation()),
                ),
            ],
            $setting instanceof ColorSetting => [
                new Assert\Required(),
                new Assert\Type('string'),
                new Assert\Regex(ColorSetting::Pattern),
            ],
            $setting instanceof NumberSetting => [
                new Assert\Required(),
                new Assert\Type('numeric'),
            ],
            $setting instanceof SelectSetting => [
                new Assert\Required(),
                new Assert\Type('string'),
                new Assert\Choice(
                    array_map(
                        fn (SelectOption $option) => $option->value,
                        $setting->options,
                    ),
                ),
            ],
            $setting instanceof TextareaSetting => [
                new Assert\Required(),
                new Assert\Type('string'),
                new Assert\Length(max: TextareaSetting::LimitLength),
            ],
            $setting instanceof TextSetting => [
                new Assert\Required(),
                new Assert\Type('string'),
                new Assert\Length(max: TextSetting::LimitLength),
            ],
            $setting instanceof EditorSetting => [
                new Assert\Required(),
                new Assert\Type('string'),
                new Assert\Length(max: EditorSetting::LimitLength),
            ],
            default => [],
        };

        return $constraints;
    }

    /**
     * Check if the value is boolean convertible
     *
     * @param mixed $value
     * @return boolean
     */
    private function isBoolenaConvertible(
        mixed $value,
    ): bool {
        return filter_var(
            $value,
            FILTER_VALIDATE_BOOLEAN,
        ) !== null;
    }
}
