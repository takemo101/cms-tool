<?php

use CmsTool\Cache\FilesystemAdapterFactory;
use Symfony\Component\Cache\Marshaller\DefaultMarshaller;

return [

    // Whether to enable cache
    // This setting is reflected when using ControlledCache
    'enabled' => (bool) env('CACHE_ENABLED', true),

    // CacheAdapterFactory implementation class name
    'factory' => FilesystemAdapterFactory::class,

    // MarshallerInterface implementation class name
    'marshaller' => DefaultMarshaller::class,

    // Default lifetime seconds
    'lifetime' => 21600, // 6 hours

    // FilesystemAdapter options
    'filesystem' => [

        // Cache directory path
        'directory' => storage_path('cache/data'),
    ],
];
