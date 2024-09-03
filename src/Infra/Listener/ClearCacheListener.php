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
        $this->cache->clear();
        $this->memo->clear();
    }
}
