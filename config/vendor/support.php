<?php

use CmsTool\Support\Encrypt\EncryptCipher;

return [
    'encrypt' => [

        // Encryption cipher
        'cipher' => EncryptCipher::AES_128_CBC,

        // Cryptocation key used by default
        'key' => env('APP_KEY'),
    ],

    'hash' => [

        // Default hash cost
        'cost' => 10,
    ],

    'translation' => [

        // Settings for translation by file
        'file' => [

            // Refer to translation file
            'locations' => [
                //
            ],
        ]
    ],

    'access_log' => [

        // Enable access log writing
        'enabled' => (bool) env('ACCESS_LOG_ENABLED', false),

        // File
        'file' => [
            // Log directory path
            'path' => storage_path('access'),

            // Log file name
            'filename' => 'access.log',

            // Log file permission
            'permission' => 0777,
        ]
    ],
];
