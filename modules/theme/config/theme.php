<?php

use CmsTool\Theme\DefaultThemeFinder;
use CmsTool\Theme\DefaultThemeLoader;

return [

    // ThemeFinder implementation class name
    'finder' => DefaultThemeFinder::class,

    // ThemeLoader implementation class name
    'loader' => DefaultThemeLoader::class,

    // Directory to place themes
    'locations' => [],

    // ID of the theme used by default
    'default' => 'simply',
];
