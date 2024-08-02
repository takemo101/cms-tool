<?php

return [

    // Whether to enable cache
    // This setting is reflected when using ControlledCache
    'enabled' => (bool) env('CACHE_ENABLED', true),

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

    // This configuration is used to modify the dependencies for cache.
    'dependencies' => [
        // CmsTool\Cache\Contract\CacheItemPoolFactory::class => CmsTool\Cache\FilesystemCacheItemPoolFactory::class,
    ],
];
