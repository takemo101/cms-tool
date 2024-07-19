<?php

namespace CmsTool\Theme\Contract;

use CmsTool\Theme\Exception\ThemeLoadException;
use CmsTool\Theme\Theme;

interface ThemeCustomizationLoader
{
    /**
     * Load theme customization data and return it as an object.
     *
     * @param Theme $theme
     * @return array<string,array<string,mixed>>
     * @throws ThemeLoadException
     */
    public function load(Theme $theme): array;
}
