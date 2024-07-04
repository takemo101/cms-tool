<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\SchemaSettingType;

/**
 * Text input setting
 *
 * @extends AbstractTextInputSetting<string>
 */
class SelectSetting extends AbstractInputSetting
{
    /**
     * @var SchemaSettingType
     */
    public const Type = SchemaSettingType::Select;

    /**
     * @var string
     */
    public const DefaultValueIfNotSet = '';

    /**
     * @var SelectOption[]
     */
    public readonly array $options;

    /**
     * constructor
     *
     * @param string $id
     * @param string $label
     * @param string|null $default
     * @param SelectOption ...$options
     */
    public function __construct(
        string $id,
        string $label,
        mixed $default = null,
        SelectOption ...$options,
    ) {
        parent::__construct(
            id: $id,
            label: $label,
            default: $default,
        );

        assert(
            empty($options) === false,
            'The setting options must not be empty',
        );

        $this->options = $options;
    }

    /**
     * {@inheritDoc}
     *
     * @param array{
     *   id: string,
     *   label: string,
     *   options: array{
     *     value: string,
     *     label: string,
     *   }[],
     *   default?: string,
     * } $data
     */
    public static function fromArray(array $data): static
    {
        /**
         * @var array{
         *   value: string,
         *   label: string,
         * }[]|string[] $options
         */
        $options = $data['options'] ?? [];

        return new self(
            $data['id'] ?? ArrayKeyMissingException::throw('id'),
            $data['label'] ?? ArrayKeyMissingException::throw('label'),
            $data['default']  ?? static::DefaultValueIfNotSet,
            ...array_map(
                // If the option is a string, create a new SelectOption with the same value and label
                function (string|array $option) {
                    return is_string($option)
                        ? new SelectOption($option, $option)
                        : new SelectOption($option['value'], $option['label']);
                },
                $options,
            ),
        );
    }
}
