<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\SchemaSettingType;

/**
 * Color input setting
 *
 * @extends AbstractTextInputSetting<string>
 */
class ColorSetting extends AbstractInputSetting
{
    /**
     * @var SchemaSettingType
     */
    public const Type = SchemaSettingType::Color;

    /**
     * @var string
     */
    public const DefaultValueIfNotSet = '#FFFFFF';

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'default' => $this->default,
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @param array{
     *   id: string,
     *   label: string,
     *   default?: string,
     * } $data
     * @throws ArrayKeyMissingException
     */
    public static function fromArray(array $data): static
    {
        return new self(
            id: $data['id'] ?? ArrayKeyMissingException::throw('id'),
            label: $data['label'] ?? ArrayKeyMissingException::throw('label'),
            default: $data['default'] ?? static::DefaultValueIfNotSet,
        );
    }
}
