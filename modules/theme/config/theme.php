<?php

use CmsTool\Theme\DefaultActiveThemeIdMatcher;
use CmsTool\Theme\DefaultThemeAccessor;
use CmsTool\Theme\DefaultThemeAssetFinfoFactory;
use CmsTool\Theme\DefaultThemeFinder;
use CmsTool\Theme\Schema\DefaultThemeCustomizationAccessor;

return [

    // ThemeFinder implementation class name
    'finder' => DefaultThemeFinder::class,

    'accessors' => [
        // ThemeAccessor implementation class name
        'theme' => DefaultThemeAccessor::class,

        // ThemeCustomizationAccessor implementation class name
        'customization' => DefaultThemeCustomizationAccessor::class,
    ],

    // ThemeAssetFinfoFactory implementation class name
    'asset_finfo_factory' => DefaultThemeAssetFinfoFactory::class,

    // ActiveThemeIdMatcher implementation class name
    'active_id_matcher' => DefaultActiveThemeIdMatcher::class,

    // Directory to place themes
    'locations' => [
        base_path('themes'),
    ],

    // Directory to place themes when copying
    'copy' => base_path('themes'),

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
