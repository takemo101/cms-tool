<?php

namespace Takemo101\CmsTool\Http\Validator;

use CmsTool\Session\Csrf\CsrfGuard;
use CmsTool\Theme\Schema\Setting\AbstractInputSetting;
use CmsTool\Theme\Schema\Setting\CheckboxSetting;
use CmsTool\Theme\Schema\Setting\ColorSetting;
use CmsTool\Theme\Schema\Setting\EditorSetting;
use CmsTool\Theme\Schema\Setting\NumberSetting;
use CmsTool\Theme\Schema\Setting\SelectOption;
use CmsTool\Theme\Schema\Setting\SelectSetting;
use CmsTool\Theme\Schema\Setting\TextareaSetting;
use CmsTool\Theme\Schema\Setting\TextInputFormat;
use CmsTool\Theme\Schema\Setting\TextSetting;
use CmsTool\Theme\Schema\ThemeSchema;
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
     * Validate the body.
     * $isStrict is used when performing strict validation during data saving.
     *
     * @param ThemeSchema $schema
     * @param array<string,mixed> $body
     * @param bool $isStrict Check if the body is strict
     * @return ConstraintViolationListInterface
     */
    public function validate(
        ThemeSchema $schema,
        array $body,
        bool $isStrict = false,
    ): ConstraintViolationListInterface {

        return $this->validator->validate(
            $this->filter($body),
            $this->getConstraint(
                schema: $schema,
                isStrict: $isStrict,
            ),
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
     * @param ThemeSchema $schema
     * @param bool $isStrict Check if the body is strict
     * @return Constraint
     */
    private function getConstraint(
        ThemeSchema $schema,
        bool $isStrict = false,
    ): Constraint {
        $constraints = [];

        foreach ($schema->settings as $settings) {

            $settingsConstraints = [];

            foreach ($settings->getInputSettings() as $setting) {
                $settingsConstraints[$setting->id->value()] = $this->createSchemaSettingConstraints(
                    setting: $setting,
                    isStrict: $isStrict,
                );
            }

            $constraints[$settings->id->value()] = new Assert\Collection($settingsConstraints);
        }

        return new Assert\Collection($constraints);
    }

    /**
     * Create schema setting constraints
     *
     * @param AbstractInputSetting $setting
     * @param bool $isStrict Check if the body is strict
     * @return Constraint[]
     */
    private function createSchemaSettingConstraints(
        AbstractInputSetting $setting,
        bool $isStrict = false,
    ): array {
        /** @var Constraint[] */
        $constraints = match (true) {
            $setting instanceof CheckboxSetting => [
                new Assert\Optional(
                    new Assert\Callback(fn (
                        mixed $value,
                        ExecutionContextInterface $context,
                    ) => $this->isBooleanConvertible($value)
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
                new Assert\AtLeastOneOf([
                    new Assert\Blank(),
                    new Assert\Type('numeric'),
                ]),
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
                ...(
                    $isStrict
                    ? match ($setting->format) {
                        TextInputFormat::Email => [
                            new Assert\Type('string'),
                            new Assert\Email(),
                        ],
                        TextInputFormat::Url =>
                        [
                            new Assert\Type('string'),
                            new Assert\Url(),
                        ],
                        default => [
                            new Assert\Type('string'),
                        ],
                    }
                    : [
                        new Assert\Type('string')
                    ]
                ),
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
    private function isBooleanConvertible(
        mixed $value,
    ): bool {
        return filter_var(
            $value,
            FILTER_VALIDATE_BOOLEAN,
        ) !== null;
    }
}
