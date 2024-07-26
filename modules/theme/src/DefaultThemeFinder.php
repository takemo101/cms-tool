<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Contract\ThemeFinder;
use CmsTool\Theme\Exception\NotFoundThemeException;
use DI\Attribute\Inject;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Filesystem\PathHelper;

class DefaultThemeFinder implements ThemeFinder
{
    /**
     * @var array<string,string>
     */
    private array $themes = [];

    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     * @param PathHelper $helper
     * @param string[] $locations
     * @param string[] $paths
     */
    public function __construct(
        protected readonly LocalFilesystem $filesystem,
        protected readonly PathHelper $helper = new PathHelper(),
        #[Inject('config.theme.locations')]
        private array $locations = [],
        #[Inject('config.theme.paths')]
        private array $paths = [],
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function exists(ThemeId $id): bool
    {
        try {
            $this->find($id);
        } catch (NotFoundThemeException) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function find(ThemeId $id): string
    {
        if ($path = $this->themes[$id->value()] ?? false) {
            return $path;
        }

        if ($path = $this->findInLocations($id)) {
            return $this->themes[$id->value()] = $path;
        }

        if ($path = $this->findInPaths($id)) {
            return $this->themes[$id->value()] = $path;
        }

        throw new NotFoundThemeException($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findAll(): array
    {
        /** @var array<string,string> */
        $themes = $this->themes;

        foreach ($this->locations as $location) {
            $pattern = $this->helper->join($location, '*');

            $directories = $this->filesystem->glob($pattern) ?? [];

            foreach ($directories as $directory) {
                $id = $this->helper->basename($directory);

                if (isset($themes[$id])) {
                    continue;
                }

                $path = $this->helper->join($directory, ThemeConfig::MetaFilename);

                if ($this->filesystem->exists($path)) {
                    $themes[$id] = $path;
                }
            }
        }

        foreach ($this->paths as $directory) {
            $currentThemeId = $this->getThemeIdFromDirectoryPath($directory);

            if (isset($themes[$currentThemeId->value()])) {
                continue;
            }

            $path = $this->helper->join($directory, ThemeConfig::MetaFilename);

            if ($this->filesystem->exists($path)) {
                $themes[$currentThemeId->value()] = $path;
            }
        }

        $this->themes = $themes;

        return $themes;
    }


    /**
     * Get the path to the theme file from the locations.
     *
     * @param ThemeId $id
     * @return string|null
     */
    private function findInLocations(ThemeId $id): ?string
    {
        foreach ($this->locations as $location) {
            $path = $this->helper->join(
                $location,
                $id->value(),
                ThemeConfig::MetaFilename,
            );

            if ($this->filesystem->exists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Get the path to the theme file from the paths.
     *
     * @param ThemeId $id
     * @return string|null
     */
    private function findInPaths(ThemeId $id): ?string
    {
        foreach ($this->paths as $directory) {
            $currentThemeId = $this->getThemeIdFromDirectoryPath($directory);

            if (!$currentThemeId->equals($id)) {
                continue;
            }

            $path = $this->helper->join($directory, ThemeConfig::MetaFilename);

            if ($this->filesystem->exists($path)) {
                return $path;
            }
        }
    }

    /**
     * Get the theme ID from the theme directory path.
     *
     * @param string $directoryPath
     * @return ThemeId
     */
    private function getThemeIdFromDirectoryPath(string $directoryPath): ThemeId
    {
        // The basename of the path string is treated as the theme ID.
        return new ThemeId($this->helper->basename($directoryPath));
    }

    /**
     * {@inheritDoc}
     */
    public function addLocation(string $location): void
    {
        $this->locations = array_unique([
            ...$this->locations,
            $this->filesystem->realpath($location) ?? $location,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function addPath(string $path): void
    {
        $this->paths = array_unique([
            ...$this->paths,
            $this->filesystem->realpath($path) ?? $path,
        ]);
    }

    /**
     * Get the locations to search for themes.
     *
     * @return array<string,string>
     */
    public function getLocations(): array
    {
        return $this->locations;
    }

    /**
     * Get the paths to search for themes.
     *
     * @return array<string,string>
     */
    public function getPaths(): array
    {
        return $this->paths;
    }
}
