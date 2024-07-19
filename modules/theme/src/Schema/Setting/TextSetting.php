<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\SchemaSettingId;
use CmsTool\Theme\Schema\SchemaSettingType;

/**
 * Single line input setting
 *
 * @extends AbstractTextInputSetting<string>
 */
class TextSetting extends AbstractTextInputSetting
{
    /**
     * @var SchemaSettingType
     */
    public const Type = SchemaSettingType::Text;

    /**
     * @var int
     */
    public const LimitLength = 500;

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    protected function getValueIfNotSet(): mixed
    {
        return '';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function extractCustomizationValueOrNotSet(array $data): mixed
    {
        /** @var string */
        $value = $data[$this->id->value()] ?? $this->getValueIfNotSet();

        return $this->sliceStringToLimit($value);
    }

    /**
     * Slice the value to the limit length.
     *
     * @param string $value
     * @return string
     */
    private function sliceStringToLimit(string $value): string
    {
        return mb_substr($value, 0, self::LimitLength);
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
            'placeholder' => $this->placeholder,
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @param array{
     *   id?: string,
     *   label?: string,
     *   default?: string,
     *   placeholder?: string,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: new SchemaSettingId($data['id'] ?? ArrayKeyMissingException::throw('id')),
            label: $data['label'] ?? ArrayKeyMissingException::throw('label'),
            default: $data['default'] ?? null,
            placeholder: $data['placeholder'] ?? null,
        );
    }
}
