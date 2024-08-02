<?php

namespace CmsTool\Theme\Schema;

use CmsTool\Theme\Contract\ThemeCustomizationAccessor;
use CmsTool\Theme\Exception\ThemeLoadException;
use CmsTool\Theme\Exception\ThemeSaveException;
use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemePathHelper;
use DI\Attribute\Inject;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Filesystem\PathHelper;

class DefaultThemeCustomizationAccessor implements ThemeCustomizationAccessor
{
    /**
     * @var array<string,array<string,array<string,mixed>>>
     */
    private array $cache = [];

    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     * @param ThemePathHelper $helper
     */
    public function __construct(
        private readonly LocalFilesystem $filesystem,
        #[Inject(ThemePathHelper::class)]
        private readonly ThemePathHelper $helper = new ThemePathHelper(new PathHelper()),
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function save(Theme $theme, array $data): void
    {
        $path = $this->helper->getCustomizationDataPath($theme);

        // If the theme is read-only, there is a possibility that the path to the temporary directory does not exist,
        // so we need to add a process to create the directory before the save operation.
        $this->helper->makeTemporaryDirectoryOrSkip(
            theme: $theme,
            filesystem: $this->filesystem,
        );

        try {
            $json = json_encode(
                $theme->refineCustomizationWithDefaults($data),
                JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT,
            );
        } catch (\JsonException $e) {
            throw ThemeSaveException::encodeError($path, $e);
        }

        if (!$json) {
            throw ThemeSaveException::encodeError($path);
        }

        if (!$this->filesystem->write($path, $json)) {
            throw ThemeSaveException::notWritableError($path);
        }

        $this->cache[$path] = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function load(Theme $theme): array
    {
        $path = $this->helper->getCustomizationDataPath($theme);

        if ($data = $this->cache[$path] ?? false) {
            return $data;
        }

        // If the file does not exist, return the default data
        if ($this->filesystem->exists($path) === false) {
            return $theme->refineCustomizationWithDefaults();
        }

        $content = $this->filesystem->read($path);

        if (!$content) {
            throw ThemeLoadException::notFoundContent($path);
        }

        /** @var array<string,array<string,mixed>> */
        $data = json_decode($content, true);

        if (
            !is_array($data)
            || json_last_error() !== JSON_ERROR_NONE
        ) {
            throw ThemeLoadException::decodeError($path);
        }

        return $theme->refineCustomizationWithDefaults($data);
    }
}
