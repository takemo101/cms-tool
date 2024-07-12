<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\SchemaSettingId;
use CmsTool\Theme\Schema\SchemaSettingType;

/**
 * Text input setting
 *
 * @extends AbstractInputSetting<string>
 */
class SelectSetting extends AbstractInputSetting
{
    /**
     * @var SchemaSettingType
     */
    public const Type = SchemaSettingType::Select;

    /**
     * @var SelectOption[]
     */
    public readonly array $options;

    /**
     * constructor
     *
     * @param SchemaSettingId $id
     * @param string $label
     * @param string|null $default
     * @param SelectOption ...$options
     */
    public function __construct(
        SchemaSettingId $id,
        string $label,
        mixed $default = null,
        SelectOption ...$options,
    ) {
        assert(
            empty($options) === false,
            'The setting options must not be empty',
        );

        $this->options = $options;

        parent::__construct(
            id: $id,
            label: $label,
            default: $default,
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    protected function getValueIfNotSet(): mixed
    {
        $options = $this->options;

        return $options[0]->value;
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
            'options' => array_map(
                fn (SelectOption $option) => $option->toArray(),
                $this->options,
            ),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @param array{
     *   id?: string,
     *   label?: string,
     *   options?: array{
     *     value: string,
     *     label: string,
     *   }[],
     *   default?: string,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        /**
         * @var array{
         *   value: string,
         *   label: string,
         * }[]|string[] $options
         */
        $options = $data['options'] ?? [];

        return new self(
            new SchemaSettingId($data['id'] ?? ArrayKeyMissingException::throw('id')),
            $data['label'] ?? ArrayKeyMissingException::throw('label'),
            $data['default'] ?? null,
            ...array_map(
                // If the option is a string, create a new SelectOption with the same value and label
                function (string|array $option) {
                    return is_string($option)
                        ? new SelectOption($option, $option)
                        : new SelectOption($option['value'], $option['label']);
                },
                $options,
            ),
        );
    }
}
