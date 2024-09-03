<?php

namespace Takemo101\CmsTool\Infra\Cache;

use CmsTool\Cache\Contract\MemoCache;

interface ApiMemoCache extends MemoCache
{
    /**
     * Clear all cache.
     *
     * @return boolean If the cache is cleared successfully, it returns true.
     */
    public function clear(): bool;
}
