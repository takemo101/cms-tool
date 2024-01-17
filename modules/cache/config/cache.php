<?php

use CmsTool\Cache\FilesystemCacheItemPoolFactory;

return [

    // Whether to enable cache
    // This setting is reflected when using ControlledCache
    'enabled' => (bool) env('CACHE_ENABLED', true),

    // CacheItemPoolFactory implementation class name
    'factory' => FilesystemCacheItemPoolFactory::class,

    // Default lifetime seconds
    'lifetime' => 21600, // 6 hours

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
