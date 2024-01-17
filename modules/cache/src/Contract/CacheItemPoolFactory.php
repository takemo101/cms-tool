<?php

namespace CmsTool\Cache\Contract;

use Psr\Cache\CacheItemPoolInterface;

interface CacheItemPoolFactory
{
    /**
     * @return CacheItemPoolInterface
     */
    public function create(): CacheItemPoolInterface;
}
