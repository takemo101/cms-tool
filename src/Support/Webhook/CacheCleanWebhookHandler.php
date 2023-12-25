<?php

namespace Takemo101\CmsTool\Support\Webhook;

use Psr\Cache\CacheItemPoolInterface;

class CacheCleanWebhookHandler implements WebhookHandler
{
    /**
     * constructor
     *
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(
        private CacheItemPoolInterface $cache,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function handle(array $payload): void
    {
        $this->cache->clear();
    }
}
