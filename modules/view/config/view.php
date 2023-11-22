<?php

use CmsTool\View\Contract\Htmlable;
use CmsTool\View\DefaultTemplateFinder;
use CmsTool\View\Twig\Extension\ConfigExtension;
use CmsTool\View\Twig\Extension\RequestExtension;
use CmsTool\View\Twig\Extension\FiltersExtension;
use CmsTool\View\Twig\Extension\FormExtension;
use CmsTool\View\Twig\Extension\FunctionsExtension;
use CmsTool\View\Twig\Extension\RouteExtension;
use CmsTool\View\Twig\TwigTemplateRenderer;
use Takemo101\Chubby\Contract\Renderable;

return [

    // TemplateFinder implementation class name
    'finder' => DefaultTemplateFinder::class,

    // TemplateRenderer implementation class name
    'renderer' => TwigTemplateRenderer::class,

    'locations' => [
        base_path('resources/views'),
    ],

    'namespaces' => [
        //
    ],

    'extensions' => [
        'twig',
        'html.twig',
    ],

    'twig' => [
        // https://github.com/twigphp/Twig/blob/3.x/src/Environment.php
        'environment' => [

            // When set to true, it automatically set "auto_reload" to true as well (default to false).
            'debug' => (bool) env('APP_DEBUG', false),

            // The charset used by the templates
            // (default to UTF-8).
            'charset' => 'utf-8',

            // An absolute path where to store the compiled templates,
            // a \Twig\Cache\CacheInterface implementation,
            // or false to disable compilation cache (default).
            'cache' => storage_path('cache/views'),

            // Whether to reload the template if the original source changed.
            // If you don't provide the auto_reload option, it will be
            // determined automatically based on the debug value.
            'auto_reload' => true,

            // Whether to ignore invalid variables in templates
            // (default to false).
            'strict_variables' => false,

            // Whether to enable auto-escaping (default to html):
            // * false: disable auto-escaping
            // * html, js: set the autoescaping to one of the supported strategies
            // * name: set the autoescaping strategy based on the template name extension
            // * PHP callback: a PHP callback that returns an escaping strategy based on the template "name"
            'autoescape' => 'html',

            // A flag that indicates which optimizations to apply
            // (default to -1 which means that all optimizations are enabled;
            // set it to 0 to disable).
            'optimizations' => -1,
        ],

        // When set, the output of the `__toString` method of the following classes will not be escaped
        'safe_classes' => [
            Htmlable::class => ['html'],
            Renderable::class => ['html'],
        ],

        // Set the class name of the Twig Extension to be enabled
        'extensions' => [
            ConfigExtension::class,
            RequestExtension::class,
            RouteExtension::class,
            FiltersExtension::class,
            FunctionsExtension::class,
            FormExtension::class,
        ],

        // Set up functions to be used in Twig
        // https://twig.symfony.com/doc/3.x/advanced.html#functions
        'functions' => [
            // 'twig-function-name' => 'php-function-name' or 'php-function-name',

            'collect',
        ],

        // Set up filters to be used in Twig
        // https://twig.symfony.com/doc/3.x/advanced.html#filters
        'filters' => [
            // 'twig-filter-name' => 'php-function-name' or 'php-function-name',

            'get' => 'data_get',
        ],
    ]

];
