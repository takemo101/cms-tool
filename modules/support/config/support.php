<?php

use CmsTool\Support\Encrypt\DefaultEncrypter;
use CmsTool\Support\Encrypt\EncryptCipher;
use CmsTool\Support\Hash\BcryptHasher;
use CmsTool\Support\JsonAccess\DefaultJsonAccessor;
use CmsTool\Support\Translation\DefaultTranslator;
use CmsTool\Support\Translation\SymfonyTranslationLoaderType;
use CmsTool\Support\Translation\SymfonyTranslatorFactory;

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

        // Default json accessor class
        'accessor' => DefaultJsonAccessor::class,
    ],

    'translation' => [

        // Default translator class
        'translator' => DefaultTranslator::class,

        // Symfony translator configuration
        'symfony' => [

            // The loader type to use
            'loaders' => [
                SymfonyTranslationLoaderType::JSON->value,
            ],

            // Refer to translation file
            // domain => location
            'locations' => [
                'messages' => base_path('resources', 'lang', 'messages'),
                'attributes' => base_path('resources', 'lang', 'attributes'),
                SymfonyTranslatorFactory::ValidationDomain => base_path('resources', 'lang', SymfonyTranslatorFactory::ValidationDomain),
            ],

            // Refer to translation locale
            // locale => locale extention (ex: xxxx.'ja'.json)
            'locales' => [
                'ja' => 'ja',
            ],

            // Refer to translation domains
            'domains' => [
                //
            ],
        ],
    ],
];
