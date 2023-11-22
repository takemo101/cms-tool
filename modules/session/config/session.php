<?php

use CmsTool\Session\NativePhpSessionFactory;

return [
    'factory' => NativePhpSessionFactory::class,

    // FlashSession classes
    'flashes' => [
        //
    ],

    // PhpSession options
    // reference: \Odan\Session\PhpSession
    'options' => [

        'name' => 'app',

        'lifetime' => 7200,
    ],

    // Csrf options
    'csrf' => [

        'storage_limit' => 200,

        'strength' => 16,

        'persistent_token_mode' => true,
    ]
];
