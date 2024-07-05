<?php

namespace CmsTool\Theme\Schema;

use CmsTool\Theme\Contract\ThemeCustomizationAccessor;
use CmsTool\Theme\Theme;

class DefaultThemeCustomizationAccessor implements ThemeCustomizationAccessor
{
    /**
     * {@inheritDoc}
     */
    public function load(Theme $theme): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function save(Theme $theme, array $data): void
    {
        //
    }
}
