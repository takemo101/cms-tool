<?php

return [

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

    // This configuration is used to modify the dependencies for theme.
    'dependencies' => [
        // CmsTool\Theme\Contract\ThemeFinder::class => DefaultThemeFinder::class,
        // CmsTool\Theme\Contract\ThemeAccessor::class => DefaultThemeAccessor::class,
        // CmsTool\Theme\Contract\ActiveThemeIdMatcher::class => DefaultActiveThemeIdMatcher::class,
        // CmsTool\Theme\Contract\ThemeAssetFinfoFactory::class => DefaultThemeAssetFinfoFactory::class,
        // CmsTool\Theme\Contract\ThemeCustomizationAccessor::class => DefaultThemeCustomizationAccessor::class,
    ],
];
