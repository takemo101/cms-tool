<?php

namespace Takemo101\CmsTool\Infra\Cache;

use CmsTool\Cache\FilesystemCacheItemPoolFactory;
use CmsTool\Cache\PsrMemoCache;
use DI\Attribute\Inject;

class FilesystemApiMemoCacheFactory
{
    /**
     * constructor
     *
     * @param FilesystemCacheItemPoolFactory $factory
     * @param array<string,mixed> $options
     * @param boolean $enabled
     * @param int<1,max> $lifetime
     */
    public function __construct(
        private readonly FilesystemCacheItemPoolFactory $factory,
        #[Inject('config.system.api_cache.filesystem')]
        private readonly array $options = [],
        #[Inject('config.system.api_cache.enabled')]
        private readonly bool $enabled = true,
        #[Inject('config.system.api_cache.lifetime')]
        private readonly int $lifetime = 21600,
    ) {
        //
    }

    /**
     * Create a new instance of ApiMemoCache
     *
     * @return ApiMemoCache
     */
    public function create(): ApiMemoCache
    {
        $pool = new PsrMemoCache(
            $this->factory->create($this->options),
            $this->enabled,
            $this->lifetime,
        );

        return new PsrApiMemoCache($pool);
    }
}
