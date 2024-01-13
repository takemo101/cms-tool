<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Contract\ActiveThemeIdMatcher;
use CmsTool\Theme\Contract\ThemeAccessor;
use CmsTool\Theme\Exception\ThemeLoadException;
use CmsTool\Theme\Exception\ThemeSaveException;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Filesystem\PathHelper;

class DefaultThemeAccessor implements ThemeAccessor
{
    /**
     * @var array<string,Theme>
     */
    private array $cache = [];

    /**
     * constructor
     *
     * @param ActiveThemeIdMatcher $matcher
     * @param LocalFilesystem $filesystem
     * @param ThemePathHelper $helper
     */
    public function __construct(
        private readonly ActiveThemeIdMatcher $matcher,
        private readonly LocalFilesystem $filesystem,
        private readonly ThemePathHelper $helper = new ThemePathHelper(new PathHelper()),
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function save(Theme $theme): void
    {
        $path = $this->helper->getThemeSettingPath($theme);

        try {
            $json = json_encode(
                $theme->meta->toArray(),
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

        $this->cache[$path] = $theme;
    }

    /**
     * {@inheritDoc}
     */
    public function load(string $path): Theme
    {
        if ($theme = $this->cache[$path] ?? false) {
            return $theme;
        }

        $content = $this->filesystem->read($path);

        if (!$content) {
            throw ThemeLoadException::notFoundContent($path);
        }

        /** @var array{
         *  uid:string,
         *  name:string,
         *  version:string,
         *  content:string,
         *  images?:string[],
         *  tags?:string[],
         *  link?:?string,
         *  preset?:?string,
         *  author:array{
         *   name:string,
         *   link?:?string,
         *  },
         *  readonly?:bool,
         *  extension?:array<string,mixed>,
         * }
         */
        $data = json_decode($content, true);

        if (
            !is_array($data)
            || json_last_error() !== JSON_ERROR_NONE
        ) {
            throw ThemeLoadException::decodeError($path);
        }

        $directory = $this->helper->extractThemeDirectory($path);

        $themeId = $this->helper->extractThemeId($directory);

        $theme = new Theme(
            id: $themeId,
            directory: $directory,
            active: $this->matcher->isMatch($themeId),
            meta: ThemeMeta::fromArray($data),
        );

        $this->cache[$path] = $theme;

        return $theme;
    }
}
