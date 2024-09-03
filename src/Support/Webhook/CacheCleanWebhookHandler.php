<?php

namespace Takemo101\CmsTool\Support\Webhook;

use Psr\Cache\CacheItemPoolInterface;
use Takemo101\CmsTool\Infra\Cache\ApiMemoCache;

class CacheCleanWebhookHandler implements WebhookHandler
{
    /**
     * constructor
     *
     * @param CacheItemPoolInterface $cache
     * @param ApiMemoCache $memo
     */
    public function __construct(
        private CacheItemPoolInterface $cache,
        private ApiMemoCache $memo,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function handle(array $payload): void
    {
        $this->cache->clear();
        $this->memo->clear();
    }
}
