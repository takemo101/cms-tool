<?php

use CmsTool\Theme\DefaultThemeAssetFinfoFactory;
use CmsTool\Theme\DefaultThemeFinder;
use CmsTool\Theme\DefaultThemeAccessor;
use Takemo101\CmsTool\Infra\JsonAccess\JsonAccessActiveThemeIdMatcher;
use Takemo101\CmsTool\Preset\MicroCms\Blog\BlogHook;
use Takemo101\CmsTool\Preset\MicroCms\Blog\BlogRoute;

return [

    // ThemeFinder implementation class name
    'finder' => DefaultThemeFinder::class,

    // ThemeAccessor implementation class name
    'accessor' => DefaultThemeAccessor::class,

    // ThemeAssetFinfoFactory implementation class name
    'factory' => DefaultThemeAssetFinfoFactory::class,

    // ActiveThemeIdMatcher implementation class name
    'matcher' => JsonAccessActiveThemeIdMatcher::class,

    // Directory to place themes
    'locations' => [
        base_path('themes'),
    ],

    // Directory to place themes when copying
    'copy' => base_path('themes'),

    // ID of the theme used by default
    'default' => env('DEFAULT_THEME', 'simply'),

    // ThemeRoute implementation class name
    'routes' => [
        // string => class-string<ThemeRoute>
        'microcms:blog' => BlogRoute::class,
    ],

    // ThemeHook implementation class name
    'hooks' => [
        // string => class-string<ThemeHook>
        'microcms:blog' => BlogHook::class,
    ],
];
