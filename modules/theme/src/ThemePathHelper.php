<?php

namespace CmsTool\Theme;

use DI\Attribute\Inject;
use Takemo101\Chubby\Filesystem\PathHelper;

class ThemePathHelper
{
    /**
     * constructor
     *
     * @param PathHelper $helper
     * @param string $temporaryDirectory
     */
    public function __construct(
        private readonly PathHelper $helper,
        #[Inject('config.theme.temporary')]
        private readonly string $temporaryDirectory = 'tmp',
    ) {
        //
    }

    /**
     * Theme location from id
     *
     * @param string $location
     * @param ThemeId $id
     * @return string
     */
    public function getThemeLocation(
        string $location,
        ThemeId $id,
    ): string {
        return $this->helper->join(
            $location,
            $id->value(),
        );
    }

    /**
     * Get theme path
     *
     * @param Theme $theme
     * @param string ...$paths
     * @return string
     */
    public function getThemePath(Theme $theme, string ...$paths): string
    {
        return $this->helper->join(
            $theme->directory,
            ...$paths,
        );
    }

    /**
     * Get theme setting path
     *
     * @param Theme $theme
     * @return string
     */
    public function getThemeSettingPath(Theme $theme): string
    {
        return $this->getThemePath(
            $theme,
            ThemeConfig::MetaFilename,
        );
    }

    /**
     * Get theme customization data path
     * If the theme is readonly, return a temporary path
     *
     * @param Theme $theme
     * @return string
     */
    public function getCustomizationDataPath(Theme $theme): string
    {
        return $theme->isReadonly()
            ? $this->getTemporaryPath(
                'customization',
                "{$theme->id}-data.json",
            )
            : $this->getThemePath(
                $theme,
                ThemeConfig::CustomizationDataFilename,
            );
    }

    /**
     * Get asset path
     *
     * @param Theme $theme
     * @param string ...$paths
     * @return string
     */
    public function getAssetPath(Theme $theme, string ...$paths): string
    {
        return $this->getThemePath(
            $theme,
            ThemeConfig::AssetsPath,
            ...$paths,
        );
    }

    /**
     * Get template path
     *
     * @param Theme $theme
     * @param string ...$paths
     * @return string
     */
    public function getTemplatePath(Theme $theme, string ...$paths): string
    {
        return $this->getThemePath(
            $theme,
            ThemeConfig::TemplatesPath,
            ...$paths,
        );
    }

    /**
     * Get temporary path
     *
     * @param string ...$paths
     * @return string
     */
    public function getTemporaryPath(string ...$paths): string
    {
        return $this->helper->join(
            $this->temporaryDirectory,
            ...$paths,
        );
    }

    /**
     * Extract theme directory from path
     *
     * @param string $path
     * @return string
     */
    public function extractThemeDirectory(string $path): string
    {
        return $this->helper->dirname($path);
    }

    /**
     * Extract theme id from path
     *
     * @param string $path
     * @return ThemeId
     */
    public function extractThemeId(string $path): ThemeId
    {
        return new ThemeId($this->helper->basename($path));
    }
}
