<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Schema\SchemeSettingType;

/**
 * Text input setting
 *
 * @extends AbstractTextInputSetting<string>
 */
class SelectSetting extends AbstractInputSetting
{
    /**
     * @var string
     */
    public const DefaultValueIfNotSet = '';

    /**
     * constructor
     *
     * @param string $id
     * @param string $label
     * @param SelectOption[] $options
     * @param string|null $default
     */
    public function __construct(
        string $id,
        string $label,
        public readonly array $options = [],
        mixed $default = null,
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
    }

    /**
     * {@inheritDoc}
     */
    public function cast(mixed $value): mixed
    {
        return (string) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function type(): SchemeSettingType
    {
        return SchemeSettingType::Select;
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
     * }
     */
    public static function fromArray(array $data): static
    {
        return new self(
            id: $data['id'],
            label: $data['label'],
            options: array_map(
                fn (array $option) => new SelectOption(
                    value: $option['value'],
                    label: $option['label'],
                ),
                $data['options'],
            ),
            default: $data['default']  ?? static::DefaultValueIfNotSet,
        );
    }
}
