<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Schema\SchemeSettingType;

/**
 * Color input setting
 *
 * @extends AbstractTextInputSetting<string>
 */
class ColorSetting extends AbstractInputSetting
{
    /**
     * @var string
     */
    public const DefaultValueIfNotSet = '#FFFFFF';

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
        return SchemeSettingType::Color;
    }

    /**
     * {@inheritDoc}
     *
     * @param array{
     *   id: string,
     *   label: string,
     *   default?: string,
     * }
     */
    public static function fromArray(array $data): static
    {
        return new self(
            id: $data['id'],
            label: $data['label'],
            default: $data['default'] ?? static::DefaultValueIfNotSet,
        );
    }
}
