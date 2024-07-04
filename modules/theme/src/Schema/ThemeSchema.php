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
