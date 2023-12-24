<?php

namespace CmsTool\Theme\Hook;

use CmsTool\Theme\Theme;
use Takemo101\Chubby\Hook\Hook;

interface ThemeHook
{
    /**
     * Register theme hook
     *
     * @param Theme $theme
     * @param Hook $hook
     * @return void
     */
    public function hook(
        Theme $theme,
        Hook $hook,
    ): void;
}
