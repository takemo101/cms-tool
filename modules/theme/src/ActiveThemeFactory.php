<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Contract\ThemeFinder;
use CmsTool\Theme\Contract\ThemeLoader;

class ActiveThemeFactory
{
    /**
     * constructor
     *
     * @param ThemeLoader $loader
     * @param ThemeFinder $finder
     * @param ActiveThemeId $id
     */
    public function __construct(
        private ThemeLoader $loader,
        private ThemeFinder $finder,
        private ActiveThemeId $id,
    ) {
        //
    }

    /**
     * Create the active theme.
     *
     * @return ActiveTheme
     */
    public function create(): ActiveTheme
    {
        $path = $this->finder->find((string) $this->id);

        $theme = $this->loader->load($path);

        return ActiveTheme::fromTheme($theme);
    }
}
