<?php

// Slim framework configuration


return [

    // Base path
    'base_path' => env('BASE_PATH'),

    // Error output settings
    'error' => [

        // ErrorMiddleware error display setting
        'setting' => [

            'display_error_details' => true,

            'log_errors' => true,

            'log_error_details' => true,
        ],
    ],

    // Cache control header settings
    'cache_control' => [

        'private' => true,

        'no-cache' => true,
    ],

    // Global middleware settings
    'middlewares' => [
        // class-string<MiddlewareInterface>
    ],
];
