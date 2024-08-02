<?php

use CmsTool\Support\Encrypt\DefaultEncrypter;
use CmsTool\Support\Encrypt\EncryptCipher;
use CmsTool\Support\Hash\BcryptHasher;
use CmsTool\Support\JsonAccess\DefaultJsonAccessor;
use CmsTool\Support\Translation\DefaultTranslator;
use CmsTool\Support\Translation\TranslationJsonFileAccessor;

return [
    'encrypt' => [

        // Default encrypter class
        'encrypter' => DefaultEncrypter::class,

        // Encryption cipher
        'cipher' => EncryptCipher::AES_128_CBC,

        // Cryptocation key used by default
        'key' => env('APP_KEY'),
    ],

    'hash' => [

        // Default hasher class
        'hasher' => BcryptHasher::class,

        // Default hash cost
        'cost' => 10,
    ],

    'json' => [

        // Default JsonArrayAccessor class
        'accessor' => DefaultJsonAccessor::class,
    ],

    'translation' => [

        // Default Translator class
        'translator' => DefaultTranslator::class,

        // Default TranslationAccessor class
        'accessor' => TranslationJsonFileAccessor::class,

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

    // This configuration is used to modify the dependencies for the support module
    'dependencies' => [
        // \CmsTool\Support\AccessLog\AccessLoggerFactory::class => CmsTool\Support\AccessLog\FileAccessLoggerFactory::class,
    ],
];
