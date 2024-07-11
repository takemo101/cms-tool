<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Schema\SchemaSettingId;

/**
 * @template T The type of input value corresponding to the schema setting type
 */
abstract class AbstractInputSetting extends AbstractSetting
{
    /**
     * @var T
     */
    public readonly mixed $default;

    /**
     * constructor
     *
     * @param SchemaSettingId $id
     * @param string $label
     * @param T|null $default
     */
    public function __construct(
        public readonly SchemaSettingId $id,
        public readonly string $label,
        mixed $default = null,
    ) {
        assert(
            empty($label) === false,
            'The setting label must not be empty',
        );

        assert(
            defined("static::DefaultValueIfNotSet"),
            'The DefaultValueIfNotSet constant must be defined',
        );

        $this->default = $default ?? $this->getDefaultValueIfNotSet();
    }

    /**
     * Returns the default value if the setting is not found in the theme's customization data.
     *
     * @return T
     */
    abstract protected function getDefaultValueIfNotSet(): mixed;

    /**
     * Extracts the value of the target setting from the theme's customization data.
     * If the setting is not found in the data, the default value is returned.
     *
     * @param array<string,mixed> $data The theme's customization data
     * @return T
     */
    public function extractCustomizationValue(array $data): mixed
    {
        return $data[$this->id->value()] ?? $this->default;
    }
}
