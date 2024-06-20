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

    'json' => [
        //
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
];
