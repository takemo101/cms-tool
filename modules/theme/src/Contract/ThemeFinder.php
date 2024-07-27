<?php

namespace CmsTool\Theme\Contract;

use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\ThemeId;

interface ThemeFinder
{
    /**
     * Check if the theme exists.
     *
     * @param ThemeId $id
     * @return bool
     */
    public function exists(ThemeId $id): bool;

    /**
     * Get the path to the template file from the id.
     *
     * @param ThemeId $id
     * @return string
     * @throws NotFoundThemeException
     */
    public function find(ThemeId $id): string;

    /**
     * Get all theme paths.
     *
     * @return array<string,string>
     */
    public function findAll(): array;

    /**
     * Add a location to the finder.
     *
     * @param string $location
     * @return void
     */
    public function addLocation(string $location): void;

    /**
     * Add a path to the finder.
     *
     * @param string $path The directory path where theme.json is located.
     * @return void
     */
    public function addPath(string $path): void;
}
