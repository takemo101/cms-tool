<?php

namespace CmsTool\Theme\Schema;

use CmsTool\Theme\Contract\ThemeCustomizationLoader;
use CmsTool\Theme\Schema\ThemeCustomizationPreviewer;
use CmsTool\Theme\Theme;

/**
 * Class to retrieve theme customization data.
 * Switches between returning preview data or loaded data based on the presence of preview data.
 */
class ThemeCustomizationFetcher
{
    /**
     * constructor
     *
     * @param ThemeCustomizationLoader $loader
     * @param ThemeCustomizationPreviewer $previewer
     */
    public function __construct(
        private readonly ThemeCustomizationLoader $loader,
        private readonly ThemeCustomizationPreviewer $previewer,
    ) {
        //
    }

    /**
     * Get the customization data for a theme.
     *
     * @param Theme $theme
     * @return array<string,array<string,mixed>>
     */
    public function get(Theme $theme): array
    {
        $preview = $this->previewer->get($theme->id);

        // If there is preview data, return it.
        return $preview !== false
            ? $preview
            : $this->loader->load($theme);
    }
}
