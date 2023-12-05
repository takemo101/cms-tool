<?php

return [
    // Settings to open the storage externally
    'public' => [

        // URL to open the storage externally
        'url' => env('APP_URL', 'http://localhost:8080') . '/storage',

        // Directory path to create symbolic links to public storage
        'link_path' => base_path('public/storage'),

        // Published storage directory path
        'storage_path' => storage_path('public'),
    ],
];
