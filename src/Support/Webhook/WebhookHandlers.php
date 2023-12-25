<?php

namespace Takemo101\CmsTool\Support\Webhook;

use Takemo101\Chubby\Support\ClassCollection;

/**
 * @extends ClassCollection<WebhookHandler>
 */
class WebhookHandlers extends ClassCollection
{
    public const Type = WebhookHandler::class;
}
