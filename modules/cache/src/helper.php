<?php

use CmsTool\Cache\Contract\MemoCache;
use Takemo101\Chubby\Support\ServiceLocator;
use Psr\Cache\CacheItemInterface;

if (!function_exists('cache')) {
    /**
     * Gets an item from the cache or a default value.
     *
     * @template T
     *
     * @param string $key The key of the item to retrieve from the cache
     * @param callable(CacheItemInterface):T $callback The callable to execute if the item is not found in the cache
     * @param bool $enabled default true
     * @return T
     * @throws \InvalidArgumentException
     */
    function cache(
        string $key,
        callable $callback,
        bool $enabled = true,
    ): mixed {
        /** @var MemoCache */
        $cache = ServiceLocator::container()->get(MemoCache::class);

        /** @var T */
        $value = $cache->get($key, $callback, $enabled);

        return $value;
    }
}
