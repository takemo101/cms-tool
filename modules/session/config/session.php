<?php

return [

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
    ],

    'dependencies' => [
        // CmsTool\Session\Contract\SessionFactory::class => CmsTool\Session\NativePhpSessionFactory::class,
    ],
];
