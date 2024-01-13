<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Contract\ThemeFinder;
use CmsTool\Theme\Contract\ThemeLoader;
use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\Exception\ThemeLoadException;

class ThemeQueryService
{
    /**
     * constructor
     *
     * @param ThemeFinder $finder
     * @param ThemeLoader $loader
     */
    public function __construct(
        private ThemeFinder $finder,
        private ThemeLoader $loader,
    ) {
        //
    }

    /**
     * Get theme by ID
     * If it does not exist, return null
     *
     * @param ThemeId $id
     * @return Theme
     * @throws NotFoundThemeException|ThemeLoadException
     */
    public function getOne(ThemeId $id): Theme
    {
        $path = $this->finder->find($id);

        return $this->loader->load($path);
    }

    /**
     * Get all themes
     *
     * @return Theme[]
     * @throws ThemeLoadException
     */
    public function getAll(): array
    {
        $paths = $this->finder->findAll();

        $result = [];

        foreach ($paths as $path) {
            $result[] = $this->loader->load($path);
        }

        return $result;
    }
}
