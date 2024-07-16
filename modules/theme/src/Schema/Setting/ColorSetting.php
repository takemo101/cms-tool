<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\SchemaSettingId;
use CmsTool\Theme\Schema\SchemaSettingType;

/**
 * Color input setting
 *
 * @extends AbstractInputSetting<string>
 */
class ColorSetting extends AbstractInputSetting
{
    /**
     * @var SchemaSettingType
     */
    public const Type = SchemaSettingType::Color;

    public const Pattern = '/^#[0-9a-fA-F]{6}$/';

    /**
     * constructor
     *
     * @param SchemaSettingId $id
     * @param string $label
     * @param string|null $default
     */
    public function __construct(
        SchemaSettingId $id,
        string $label,
        mixed $default = null,
    ) {
        parent::__construct(
            id: $id,
            label: $label,
            default: $default,
        );

        assert(
            preg_match(self::Pattern, $this->default) === 1,
            'The default value must be a valid hexadecimal color code',
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    protected function getValueIfNotSet(): mixed
    {
        return '#FFFFFF';
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'label' => $this->label,
            'default' => $this->default,
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @param array{
     *   id?: string,
     *   label?: string,
     *   default?: string,
     * } $data
     * @throws ArrayKeyMissingException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: new SchemaSettingId($data['id'] ?? ArrayKeyMissingException::throw('id')),
            label: $data['label'] ?? ArrayKeyMissingException::throw('label'),
            default: $data['default'] ?? null,
        );
    }
}
