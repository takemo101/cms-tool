<?php

use CmsTool\Theme\DefaultThemeAssetFinfoFactory;
use CmsTool\Theme\DefaultThemeFinder;
use CmsTool\Theme\DefaultThemeLoader;

return [

    // ThemeFinder implementation class name
    'finder' => DefaultThemeFinder::class,

    // ThemeLoader implementation class name
    'loader' => DefaultThemeLoader::class,

    // ThemeAssetFileReader implementation class name
    'reader' => DefaultThemeAssetFinfoFactory::class,

    // Directory to place themes
    'locations' => [
        base_path('resources/themes'),
        base_path('themes'),
    ],

    // ID of the theme used by default
    'default' => 'simply',

    // ThemeRoute implementation class name
    'routes' => [
        // string => class-string<ThemeRoute>
    ],
];
