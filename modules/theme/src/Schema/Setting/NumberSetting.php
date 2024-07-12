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
     * @var int
     */
    public const Step = 1;

    /**
     * constructor
     *
     * @param SchemaSettingId $id
     * @param string $label
     * @param integer $min
     * @param integer $max
     * @param integer|float $step
     * @param integer|float|null $default
     * @param string|null $placeholder
     */
    public function __construct(
        SchemaSettingId $id,
        string $label,
        public readonly int $min,
        public readonly int $max,
        public readonly int|float $step = self::Step,
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

        assert(
            $this->step > 0,
            'The step value must be greater than zero',
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return integer|float
     */
    protected function getValueIfNotSet(): mixed
    {
        return $this->min;
    }

    /**
     * {@inheritDoc}
     *
     * @return integer|float
     */
    public function extractCustomizationValueOrDefault(array $data): mixed
    {
        /** @var integer|float|string */
        $value = $data[$this->id->value()] ?? $this->default;

        if (is_string($value)) {
            $value = $this->convertStringToNumber($value);
        }

        return $this->clampValue($value);
    }

    /**
     * {@inheritDoc}
     *
     * @return integer|float
     */
    public function extractCustomizationValueOrNotSet(array $data): mixed
    {
        /** @var integer|float|string */
        $value = $data[$this->id->value()] ?? $this->getValueIfNotSet();

        if (is_string($value)) {
            $value = $this->convertStringToNumber($value);
        }

        return $this->clampValue($value);
    }

    /**
     * Converts a string value to an integer or float value.
     *
     * @param string $value
     * @return integer|float
     */
    private function convertStringToNumber(string $value): int|float
    {
        /** @var integer|false */
        $intValue = filter_var($value, FILTER_VALIDATE_INT);
        if ($intValue !== false) {
            return $intValue;
        }

        /** @var float|false */
        $floatValue = filter_var($value, FILTER_VALIDATE_FLOAT);
        if ($floatValue !== false) {
            return $floatValue;
        }

        return $this->default;
    }

    /**
     * Clamp the value to the min and max values.
     *
     * @param integer|float $value
     * @return integer|float
     */
    private function clampValue(int|float $value): int|float
    {
        if ($value < $this->min) {
            return $this->min;
        }

        if ($value > $this->max) {
            return $this->max;
        }

        return $value;
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
            'step' => $this->step ?? 1,
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
     *   step?: integer|float,
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
            step: $data['step'] ?? self::Step,
            default: $data['default'] ?? null,
            placeholder: $data['placeholder'] ?? null,
        );
    }
}
