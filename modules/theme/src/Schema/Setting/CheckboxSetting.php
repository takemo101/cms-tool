<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\SchemaSettingId;
use CmsTool\Theme\Schema\SchemaSettingType;

/**
 * Checkbox input setting
 *
 * @extends AbstractInputSetting<boolean>
 */
class CheckboxSetting extends AbstractInputSetting
{
    /**
     * @var SchemaSettingType
     */
    public const Type = SchemaSettingType::Checkbox;

    /**
     * {@inheritDoc}
     *
     * @return boolean
     */
    protected function getValueIfNotSet(): mixed
    {
        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @return boolean
     */
    public function extractCustomizationValueOrNotSet(array $data): mixed
    {
        /** @var boolean|string */
        $value = $data[$this->id->value()] ?? $this->getValueIfNotSet();

        if (is_string($value)) {
            $value = $this->convertStringToBoolean($value);
        }

        return $value;
    }

    /**
     * Converts a string value to a boolean value.
     *
     * @param string $value
     * @return boolean
     */
    private function convertStringToBoolean(string $value): bool
    {
        $boolValue = filter_var(
            $value,
            FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE,
        );

        return $boolValue ?? $this->getValueIfNotSet();
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'label' => $this->label,
            'hint' => $this->hint,
            'default' => $this->default,
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @param array{
     *   id?: string,
     *   label?: string,
     *   hint?: string,
     *   default?: boolean,
     * } $data
     * @throws ArrayKeyMissingException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: new SchemaSettingId($data['id'] ?? ArrayKeyMissingException::throw('id')),
            label: $data['label'] ?? ArrayKeyMissingException::throw('label'),
            hint: $data['hint'] ?? '',
            default: $data['default'] ?? null,
        );
    }
}
