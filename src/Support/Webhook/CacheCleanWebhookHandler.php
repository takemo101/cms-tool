<?php

namespace Takemo101\CmsTool\Support\Webhook;

use Takemo101\CmsTool\Infra\Cache\ApiMemoCache;

class CacheCleanWebhookHandler implements WebhookHandler
{
    /**
     * constructor
     *
     * @param ApiMemoCache $memo
     */
    public function __construct(
        private ApiMemoCache $memo,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function handle(array $payload): void
    {
        // When the webhook is triggered, only the content displayed on the front end needs to be updated, so we need to clear the cache for the API only.
        $this->memo->clear();
    }
}
