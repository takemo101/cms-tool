<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Contract\ActiveThemeIdMatcher;
use CmsTool\Theme\Contract\ThemeLoader;
use CmsTool\Theme\Exception\ThemeLoadException;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Filesystem\PathHelper;

class DefaultThemeLoader implements ThemeLoader
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
     * @param PathHelper $helper
     */
    public function __construct(
        private readonly ActiveThemeIdMatcher $matcher,
        private readonly LocalFilesystem $filesystem,
        private readonly PathHelper $helper = new PathHelper(),
    ) {
        //
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
            throw ThemeLoadException::invalidContent($path);
        }

        $directory = $this->helper->dirname($path);

        $id = $this->helper->basename($directory);

        $themeId = new ThemeId($id);

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
