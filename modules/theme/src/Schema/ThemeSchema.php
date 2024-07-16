<?php

namespace CmsTool\Theme\Schema;

use CmsTool\Theme\Schema\Setting\AbstractInputSetting;
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
     * Check if the schema settings contain an input setting
     *
     * @return boolean
     */
    public function isInputSettingEmpty(): bool
    {
        foreach ($this->settings as $schemaSettings) {
            foreach ($schemaSettings->settings as $setting) {
                if ($setting instanceof AbstractInputSetting) {
                    return false;
                }
            }
        }

        return true;
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
     * Refines the theme's customization data with the default values of the schema settings.
     * The customization data is passed as an array of key-value pairs for each ID.
     *
     * @param array<string,array<string,mixed>> $data The theme's customization data
     * @return array<string,array<string,mixed>>
     */
    public function refineCustomizationWithDefaults(array $data): array
    {
        /**
         * @var array<string,array<string,mixed>>
         */
        $result = [];

        foreach ($this->settings as $schemaSettings) {
            $id = $schemaSettings->id->value();

            $result[$id] = $schemaSettings->extractCustomizationValuesOrDefaults($data[$id] ?? []);
        }

        return $result;
    }

    /**
     * Refines the theme's customization data with the not set values of the schema settings.
     * The customization data is passed as an array of key-value pairs for each ID.
     *
     * @param array<string,array<string,mixed>> $data The theme's customization data
     * @return array<string,array<string,mixed>>
     */
    public function refineCustomizationWithNotSet(array $data): array
    {
        /**
         * @var array<string,array<string,mixed>>
         */
        $result = [];

        foreach ($this->settings as $schemaSettings) {
            $id = $schemaSettings->id->value();

            $result[$id] = $schemaSettings->extractCustomizationValuesOrNotSet($data[$id] ?? []);
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
