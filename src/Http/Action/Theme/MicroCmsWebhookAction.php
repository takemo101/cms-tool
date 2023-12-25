<?php

namespace Takemo101\CmsTool\Http\Action\Theme;

use Takemo101\Chubby\Http\Renderer\JsonRenderer;

class MicroCmsWebhookAction
{
    /**
     * @return JsonRenderer
     */
    public function __invoke(): JsonRenderer
    {
        return new JsonRenderer(['message' => 'ok']);
    }
}
