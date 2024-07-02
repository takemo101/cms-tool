<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Schema\SchemeSettingType;

/**
 * Numeric input setting
 *
 * @extends AbstractTextInputSetting<integer|float>
 */
class NumberSetting extends AbstractTextInputSetting
{
    /**
     * @var integer
     */
    public const DefaultValueIfNotSet = 0;

    /**
     * {@inheritDoc}
     */
    public function cast(mixed $value): mixed
    {
        return is_float($value)
            ? $value
            : (int) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function type(): SchemeSettingType
    {
        return SchemeSettingType::Number;
    }

    /**
     * {@inheritDoc}
     *
     * @param array{
     *   id: string,
     *   label: string,
     *   default?: integer|float,
     *   placeholder?: string,
     * }
     */
    public static function fromArray(array $data): static
    {
        return new self(
            id: $data['id'],
            label: $data['label'],
            default: $data['default'] ?? self::DefaultValueIfNotSet,
            placeholder: $data['placeholder'] ?? null,
        );
    }
}
