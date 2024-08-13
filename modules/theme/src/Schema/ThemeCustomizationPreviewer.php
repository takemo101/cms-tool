<?php

namespace CmsTool\Theme\Schema;

use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemeId;

/**
 * Class to manage theme customization preview data.
 */
class ThemeCustomizationPreviewer
{
    /**
     * constructor
     *
     * @param array<string,array<string,array<string,mixed>>> $previews [Theme ID => Preview data]
     */
    public function __construct(
        private array $previews = [],
    ) {
        //
    }

    /**
     * Set the preview data for a theme.
     *
     * @param ThemeId $id
     * @param array<string,array<string,mixed>> $preview
     * @return void
     */
    public function set(ThemeId $id, array $preview): void
    {
        $this->previews[$id->value()] = $preview;
    }

    /**
     * Get the preview data for a theme.
     *
     * @param ThemeId $id
     * @return array<string,array<string,mixed>>|false
     */
    public function get(ThemeId $id): array|false
    {
        return $this->previews[$id->value()] ?? false;
    }
}
