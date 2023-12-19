<?php

namespace CmsTool\Theme\Contract;

use CmsTool\Theme\Theme;
use SplFileInfo;

interface ThemeAssetFinfoFactory
{
    /**
     * Get the file information of the theme asset.
     * If the file does not exist, null is returned.
     *
     * @param Theme $theme
     * @param string ...$paths
     * @return SplFileInfo|null
     */
    public function create(Theme $theme, string ...$paths): ?SplFileInfo;
}
