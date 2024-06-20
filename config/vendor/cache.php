<?php

return [

    // Whether to enable cache
    // This setting is reflected when using ControlledCache
    'enabled' => (bool) env('CACHE_ENABLED', true),

    // Default lifetime seconds
    'lifetime' => (int) env('CACHE_LIFETIME', 21600 * 2), // 6 hours

    // FilesystemAdapter options
    'filesystem' => [

        // Cache directory path
        'path' => storage_path('cache/data'),
    ],

    // SqliteAdapter options
    'sqlite' => [

        // Cache database file path
        'path' => storage_path('cache/data/sqlite'),
    ],
];
