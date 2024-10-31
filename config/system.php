<?php

use GuzzleHttp\RequestOptions;

// CMSTOOL system unique configuration

return [
    // The path to the setting file.
    'setting' => base_path('setting.json'),

    // Route path of management screen and installation screen
    'route' => '/system',

    // Settings to send web hooks to the system
    'webhook' => [

        // Route path of webhook
        'route' => env('SYSTEM_WEBHOOK_ROUTE', '/webhook'),

        // Header name of webhook
        'header' => env('SYSTEM_WEBHOOK_HEADER', 'X-CMSTOOL-WEBHOOK-TOKEN'),

        // WebhookHandler implementation class name
        'handlers' => [
            // class-string<WebhookHandler>
        ],
    ],

    // GuzzleHttp client options
    // referrer: https://docs.guzzlephp.org/en/stable/request-options.html
    'guzzle' => [

        RequestOptions::CRYPTO_METHOD => env('GUZZLE_TLS_OPTION', false)
            ? STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT
            : null,
        RequestOptions::TIMEOUT => 60, // default: 30
    ],

    // Settings for robots.txt
    'robots_txt' => [

        // Path to robots.txt
        'path' => base_path('public', 'robots.txt'),
    ],

    // Basic authentication settings
    'basic_auth' => [

        // Enable default Basic authentication flag
        'enabled' => (bool) env('BASIC_AUTH_ENABLED', false),

        // Basic authentication realm name
        'realm' => 'Admin',

        // Default username and password for enabling Basic authentication
        'users' => [
            // 'username' => 'hashed password'
            env('BASIC_AUTH_USERNAME', 'admin') => env('BASIC_AUTH_PASSWORD', 'admin'),
        ],
    ],

    // Content api cache settings
    'api_cache' => [

        // Whether to enable cache
        // This setting is reflected when using ApiMemoCache
        'enabled' => (bool) env(
            'API_CACHE_ENABLED',
            env('CACHE_ENABLED', true),
        ),

        // Default lifetime seconds
        'lifetime' => (int) env(
            'API_CACHE_LIFETIME',
            env('CACHE_LIFETIME', 21600 * 2), // 6 hours
        ),

        // FilesystemAdapter options
        'filesystem' => [

            // Cache directory path
            'path' => storage_path('cache/api-data'),
        ],
    ]
];
