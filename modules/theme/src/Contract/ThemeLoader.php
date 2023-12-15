<?php

namespace CmsTool\Theme\Contract;

use CmsTool\Theme\Exception\ThemeLoadException;
use CmsTool\Theme\Theme;

interface ThemeLoader
{
    /**
     * Get the path to the template file from the id.
     *
     * @param string $path
     * @return Theme
     * @throws ThemeLoadException
     */
    public function load(string $path): Theme;
}
