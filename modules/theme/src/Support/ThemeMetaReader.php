<?php

namespace CmsTool\Theme\Support;

use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemeConfig;
use CmsTool\Theme\ThemePathHelper;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use RuntimeException;

class ThemeMetaReader
{
    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     * @param ThemePathHelper $path
     */
    public function __construct(
        private LocalFilesystem $filesystem,
        private ThemePathHelper $path,
    ) {
        //
    }

    /**
     * Get the theme.json contents
     *
     * @return string
     * @throws RuntimeException
     */
    public function get(Theme $theme): string
    {
        $path = $this->path->getThemePath($theme, ThemeConfig::MetaFilename);

        return $this->filesystem->read($path) ?? throw new RuntimeException(
            sprintf(
                'Theme meta file not found: %s',
                $path,
            )
        );
    }
}
