<?php

use CmsTool\Theme\DefaultActiveThemeIdMatcher;
use CmsTool\Theme\DefaultThemeAssetFinfoFactory;
use CmsTool\Theme\DefaultThemeFinder;
use CmsTool\Theme\DefaultThemeLoader;

return [

    // ThemeFinder implementation class name
    'finder' => DefaultThemeFinder::class,

    // ThemeLoader implementation class name
    'loader' => DefaultThemeLoader::class,

    // ThemeAssetFinfoFactory implementation class name
    'factory' => DefaultThemeAssetFinfoFactory::class,

    // ActiveThemeIdMatcher implementation class name
    'matcher' => DefaultActiveThemeIdMatcher::class,

    // Directory to place themes
    'locations' => [],

    // ID of the theme used by default
    'default' => 'simply',

    // ThemeRoute implementation class name
    'routes' => [
        // string => class-string<ThemeRoute>
    ],

    // ThemeHook implementation class name
    'hooks' => [
        // string => class-string<ThemeHook>
    ],
];
