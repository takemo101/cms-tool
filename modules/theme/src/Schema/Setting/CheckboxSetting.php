<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Schema\SchemeSettingType;

/**
 * Checkbox input setting
 *
 * @extends AbstractTextInputSetting<boolean>
 */
class CheckboxSetting extends AbstractInputSetting
{
    /**
     * @var boolean
     */
    public const DefaultValueIfNotSet = false;

    /**
     * {@inheritDoc}
     */
    public function cast(mixed $value): mixed
    {
        return (bool) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function type(): SchemeSettingType
    {
        return SchemeSettingType::Checkbox;
    }

    /**
     * {@inheritDoc}
     *
     * @param array{
     *   id: string,
     *   label: string,
     *   default?: boolean,
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
