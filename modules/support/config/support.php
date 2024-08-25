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

    // This configuration is used to modify the dependencies for the support module
    'dependencies' => [
        // CmsTool\Support\Encrypt\Encrypter::class => CmsTool\Support\Encrypt\DefaultEncrypter::class,
        // CmsTool\Support\Hash\Hasher::class => CmsTool\Support\Hash\BcryptHasher::class,
        // CmsTool\Support\JsonAccess\JsonArrayAccessor::class => CmsTool\Support\JsonAccess\DefaultJsonAccessor::class,
        // CmsTool\Support\Translation\TranslationAccessor::class => CmsTool\Support\Translation\TranslationJsonFileAccessor::class,
        // CmsTool\Support\Translation\Translator::class => CmsTool\Support\Translation\DefaultTranslator::class,
        // CmsTool\Support\AccessLog\AccessLoggerFactory::class => CmsTool\Support\AccessLog\FileAccessLoggerFactory::class,
        // CmsTool\Support\Dotenv\DotenvContentRepository::class => CmsTool\Support\Dotenv\LocalFileDotenvContentRepository::class,
    ],
];
