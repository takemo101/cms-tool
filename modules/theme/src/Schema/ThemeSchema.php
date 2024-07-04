<?php

namespace CmsTool\Theme\Schema;

/**
 * Manages the input schema settings for customizing themes.
 * Also serves as a collection of schema settings.
 */
readonly class ThemeSchema
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
                in_array($schemaSettings->id, $settingsIds) === false,
                'The schema settings ID must be unique',
            );

            $settingsIds[] = $schemaSettings->id;
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
}
