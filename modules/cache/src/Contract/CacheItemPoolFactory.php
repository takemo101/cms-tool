<?php

namespace CmsTool\Cache\Contract;

use Psr\Cache\CacheItemPoolInterface;

interface CacheItemPoolFactory
{
    /**
     * @param array<string,mixed> $options Cache driver options
     * @return CacheItemPoolInterface
     */
    public function create(array $options = []): CacheItemPoolInterface;
}
