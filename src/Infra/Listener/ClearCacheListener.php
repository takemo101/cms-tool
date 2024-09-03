<?php

namespace Takemo101\CmsTool\Infra\Listener;

use Psr\Cache\CacheItemPoolInterface;
use Takemo101\Chubby\Event\Attribute\AsEventListener;
use Takemo101\CmsTool\Infra\Cache\ApiMemoCache;
use Takemo101\CmsTool\Infra\Event\ThemeActivated;

#[AsEventListener(ThemeActivated::class)]
class ClearCacheListener
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
     * @return void
     */
    public function __invoke(): void
    {
        // If the theme is changed, both the API and regular cache need to be cleared as the presets for the theme will also change.
        $this->cache->clear();
        $this->memo->clear();
    }
}
