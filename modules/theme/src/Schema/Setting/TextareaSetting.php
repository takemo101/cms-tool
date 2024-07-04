<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\SchemaSettingId;
use CmsTool\Theme\Schema\SchemaSettingType;

/**
 * Multiline input setting
 *
 * @extends AbstractTextInputSetting<string>
 */
class TextareaSetting extends AbstractTextInputSetting
{
    /**
     * @var SchemaSettingType
     */
    public const Type = SchemaSettingType::Textarea;

    /**
     * @var string
     */
    public const DefaultValueIfNotSet = '';

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'label' => $this->label,
            'default' => $this->default,
            'placeholder' => $this->placeholder,
        ];
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
            id: new SchemaSettingId($data['id'] ?? ArrayKeyMissingException::throw('id')),
            label: $data['label'] ?? ArrayKeyMissingException::throw('label'),
            default: $data['default'] ?? static::DefaultValueIfNotSet,
            placeholder: $data['placeholder'] ?? null,
        );
    }
}
