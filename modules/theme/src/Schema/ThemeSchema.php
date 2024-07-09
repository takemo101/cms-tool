<?php

namespace CmsTool\Theme\Schema;

use Takemo101\Chubby\Contract\Arrayable;

/**
 * Manages the input schema settings for customizing themes.
 * Also serves as a collection of schema settings.
 *
 * @implements Arrayable<integer,array<string,mixed>>
 */
readonly class ThemeSchema implements Arrayable
{
    /**
     * @var SchemaSettings[]
     */
    public array $settings;

    /**
     * constructor
     *
     * @param SchemaSettings ...$settings
     */
    public function __construct(
        SchemaSettings ...$settings,
    ) {
        /**
         * @var string[]
         */
        $settingsIds = [];

        foreach ($settings as $schemaSettings) {
            assert(
                in_array($schemaSettings->id->value(), $settingsIds) === false,
                'The schema settings ID must be unique',
            );

            $settingsIds[] = $schemaSettings->id->value();
        }

        $this->settings = $settings;
    }

    /**
     * Check if the schema settings are empty
     *
     * @return boolean
     */
    public function isEmpty(): bool
    {
        return empty($this->settings);
    }

    /**
     * Extracts the values of the schema settings from the theme's customization data.
     * The customization data is passed as an array of key-value pairs for each ID.
     *
     * @param array<string,array<string,mixed>> $data The theme's customization data
     * @return array<string,array<string,mixed>>
     */
    public function extractCustomizationData(array $data): array
    {
        /**
         * @var array<string,array<string,mixed>>
         */
        $result = [];

        foreach ($this->settings as $schemaSettings) {
            $id = $schemaSettings->id->value();

            $result[$id] = $schemaSettings->extractCustomizationValues($data[$id] ?? []);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return array_map(
            fn (SchemaSettings $settings) => $settings->toArray(),
            $this->settings,
        );
    }
}
