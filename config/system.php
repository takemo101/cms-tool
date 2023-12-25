<?php

use Takemo101\CmsTool\Support\Webhook\CacheCleanWebhookHandler;

return [
    // The path to the setting file.
    'setting' => base_path('setting.json'),

    // Route path of management screen and installation screen
    'route' => '/system',

    // Settings to send web hooks to the system
    'webhook' => [

        // Route path of webhook
        'route' => env('SYSTEM_WEBHOOK_ROUTE', '/webhook'),

        // Header name of webhook
        'header' => env('SYSTEM_WEBHOOK_HEADER', 'X-CMSTOOL-WEBHOOK-TOKEN'),

        // WebhookHandler implementation class name
        'handlers' => [
            CacheCleanWebhookHandler::class,
        ],
    ],
];
