<?php

namespace CmsTool\Theme;

use Takemo101\Chubby\Filesystem\PathHelper;

class ThemePathHelper
{
    /**
     * constructor
     *
     * @param PathHelper $helper
     */
    public function __construct(
        private PathHelper $helper,
    ) {
        //
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
}
