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
     */
    public function __construct(
        protected readonly LocalFilesystem $filesystem,
        protected readonly PathHelper $helper = new PathHelper(),
        #[Inject('config.theme.locations')]
        private array $locations = [],
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

        throw new NotFoundThemeException($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findAll(): array
    {
        /** @var array<string,string> */
        $themes = [];

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
     * Get the locations to search for themes.
     *
     * @return array<string,string>
     */
    public function getLocations(): array
    {
        return $this->locations;
    }
}
