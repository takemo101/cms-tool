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
     * } $data
     */
    public static function fromArray(array $data): static
    {
        return new self(
            $data['id'],
            $data['label'],
            $data['default']  ?? static::DefaultValueIfNotSet,
            ...array_map(
                fn (array $option): SelectOption => new SelectOption(
                    value: $option['value'],
                    label: $option['label'],
                ),
                $data['options'],
            ),
        );
    }
}
