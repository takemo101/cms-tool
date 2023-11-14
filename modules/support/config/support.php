<?php

use CmsTool\Support\Encrypt\DefaultEncrypter;
use CmsTool\Support\Encrypt\EncryptCipher;
use CmsTool\Support\JsonAccess\DefaultJsonAccessor;

return [
    'encrypt' => [

        // Default encrypter class
        'encrypter' => DefaultEncrypter::class,

        // Encryption cipher
        'cipher' => EncryptCipher::AES_128_CBC,

        // Cryptocation key used by default
        'key' => env('APP_KEY'),
    ],

    'json' => [

        // Default json accessor class
        'accessor' => DefaultJsonAccessor::class,
    ],
];
