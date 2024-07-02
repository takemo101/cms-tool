<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Schema\SchemeSettingType;

/**
 * Multiline input setting
 *
 * @extends AbstractTextInputSetting<string>
 */
class TextareaSetting extends AbstractTextInputSetting
{
    /**
     * @var string
     */
    public const DefaultValueIfNotSet = '';

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
        return SchemeSettingType::Textarea;
    }

    /**
     * {@inheritDoc}
     *
     * @param array{
     *   id: string,
     *   label: string,
     *   default?: string,
     *   placeholder?: string,
     * } $data
     */
    public static function fromArray(array $data): static
    {
        return new self(
            id: $data['id'],
            label: $data['label'],
            default: $data['default'] ?? static::DefaultValueIfNotSet,
            placeholder: $data['placeholder'] ?? null,
        );
    }
}
