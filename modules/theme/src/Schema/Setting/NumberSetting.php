<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\SchemaSettingId;
use CmsTool\Theme\Schema\SchemaSettingType;

/**
 * Numeric input setting
 *
 * @extends AbstractTextInputSetting<integer|float>
 */
class NumberSetting extends AbstractTextInputSetting
{
    /**
     * @var SchemaSettingType
     */
    public const Type = SchemaSettingType::Number;

    /**
     * constructor
     *
     * @param SchemaSettingId $id
     * @param string $label
     * @param integer $min
     * @param integer $max
     * @param integer|float|null $default
     * @param string|null $placeholder
     */
    public function __construct(
        SchemaSettingId $id,
        string $label,
        public readonly int $min,
        public readonly int $max,
        mixed $default = null,
        ?string $placeholder = null,
    ) {
        parent::__construct(
            id: $id,
            label: $label,
            default: $default,
            placeholder: $placeholder,
        );

        assert(
            $this->min <= $this->max,
            'The minimum value must be less than or equal to the maximum value',
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return integer|float
     */
    protected function getDefaultValueIfNotSet(): mixed
    {
        return 0;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'label' => $this->label,
            'min' => $this->min,
            'max' => $this->max,
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
     *   min?: integer,
     *   max?: integer,
     *   default?: integer|float,
     *   placeholder?: string,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: new SchemaSettingId($data['id'] ?? ArrayKeyMissingException::throw('id')),
            label: $data['label'] ?? ArrayKeyMissingException::throw('label'),
            min: $data['min'] ?? ArrayKeyMissingException::throw('min'),
            max: $data['max'] ?? ArrayKeyMissingException::throw('max'),
            default: $data['default'] ?? null,
            placeholder: $data['placeholder'] ?? null,
        );
    }
}
