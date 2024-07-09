<?php

namespace CmsTool\Theme;

final class ThemeConfig
{
    /**
     * Filename for the meta information required by the theme
     */
    public const MetaFilename = 'theme.json';

    /**
     * Filename to save the theme's customization data
     */
    public const CustomizationDataFilename = 'data.json';

    /**
     * Readme file names that contain the description of the theme in Markdown
     */
    public const ReadmeFilenames = [
        'readme.md',
        'readme',
    ];

    /**
     * Directory name to store asset files such as images required by the theme
     */
    public const AssetsPath = 'assets';

    /**
     * Directory name to store template files required by the theme
     */
    public const TemplatesPath = 'templates';
}
