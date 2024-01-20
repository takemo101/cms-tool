<?php

use CmsTool\Session\NativePhpSessionFactory;
use Takemo101\CmsTool\Support\Session\FlashErrorMessages;
use Takemo101\CmsTool\Support\Session\FlashOldInputs;

return [
    'factory' => NativePhpSessionFactory::class,

    // FlashSession classes
    'flashes' => [
        FlashErrorMessages::class,
        FlashOldInputs::class,
    ],

    // PhpSession options
    // reference: \Odan\Session\PhpSession
    'options' => [

        'name' => 'app',

        'lifetime' => (int) env('SESSION_LIFETIME', 21600),
    ],

    // Csrf options
    'csrf' => [

        'storage_limit' => 200,

        'strength' => 16,

        'persistent_token_mode' => true,
    ]
];
