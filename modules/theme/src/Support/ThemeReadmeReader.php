<?php

namespace CmsTool\Theme\Support;

use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemeConfig;
use CmsTool\Theme\ThemePathHelper;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Filesystem\PathHelper;

class ThemeReadmeReader
{
    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     * @param ThemePathHelper $path
     * @param PathHelper $helper
     */
    public function __construct(
        private LocalFilesystem $filesystem,
        private ThemePathHelper $path,
        private PathHelper $helper,
    ) {
        //
    }

    /**
     * Get the contents of Readme
     * If it does not exist, return null
     *
     * @return string|null
     */
    public function get(Theme $theme): ?string
    {
        $pattern = $this->path->getThemePath($theme, '*');

        $paths = $this->filesystem->glob($pattern) ?? [];

        foreach ($paths as $path) {
            $filename = strtolower(
                $this->helper->filename($path)
            );

            if (in_array(
                $filename,
                ThemeConfig::ReadmeFilenames,
            )) {
                return $this->filesystem->read($path);
            }
        }

        return null;
    }
}
