<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Exception\ArrayKeyMissingException;
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
     * @var integer
     */
    public const DefaultValueIfNotSet = 0;

    /**
     * constructor
     *
     * @param string $id
     * @param string $label
     * @param integer $min
     * @param integer $max
     * @param T|null $default
     * @param string|null $placeholder
     */
    public function __construct(
        string $id,
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
     * @param array{
     *   id: string,
     *   label: string,
     *   min: integer,
     *   max: integer,
     *   default?: integer|float,
     *   placeholder?: string,
     * } $data
     */
    public static function fromArray(array $data): static
    {
        return new self(
            id: $data['id'] ?? ArrayKeyMissingException::throw('id'),
            label: $data['label'] ?? ArrayKeyMissingException::throw('label'),
            min: $data['min'] ?? ArrayKeyMissingException::throw('min'),
            max: $data['max'] ?? ArrayKeyMissingException::throw('max'),
            default: $data['default'] ?? self::DefaultValueIfNotSet,
            placeholder: $data['placeholder'] ?? null,
        );
    }
}
