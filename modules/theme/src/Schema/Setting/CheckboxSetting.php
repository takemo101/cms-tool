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
    protected function getDefaultValueIfNotSet(): mixed
    {
        return false;
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
     *   default?: boolean,
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
