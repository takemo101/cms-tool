<?php

use CmsTool\Cache\FilesystemAdapterFactory;
use Symfony\Component\Cache\Marshaller\DefaultMarshaller;

return [
    // CacheAdapterFactory implementation class name
    'factory' => FilesystemAdapterFactory::class,

    // MarshallerInterface implementation class name
    'marshaller' => DefaultMarshaller::class,

    // Default lifetime seconds
    'lifetime' => (int) env('CACHE_LIFETIME', 21600 * 2), // 6 hours

    // FilesystemAdapter options
    'filesystem' => [

        // Cache directory path
        'directory' => storage_path('cache/data'),
    ],
];
