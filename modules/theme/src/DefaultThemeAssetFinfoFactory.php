<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Contract\ThemeAssetFinfoFactory;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use SplFileInfo;

class DefaultThemeAssetFinfoFactory implements ThemeAssetFinfoFactory
{
    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     * @param ThemePathHelper $helper
     */
    public function __construct(
        private readonly LocalFilesystem $filesystem,
        private readonly ThemePathHelper $helper,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function create(Theme $theme, string ...$paths): ?SplFileInfo
    {
        $assetPath = $this->helper->getAssetPath($theme, ...$paths);

        if (
            !$this->filesystem->exists($assetPath)
            || !$this->filesystem->isFile($assetPath)
        ) {
            return null;
        }

        return new SplFileInfo($assetPath);
    }
}
