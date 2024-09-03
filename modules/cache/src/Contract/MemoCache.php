<?php

namespace CmsTool\Cache\Contract;

use Psr\Cache\CacheItemInterface;
use InvalidArgumentException;

/**
 * Cache the result of a callback for a key.
 */
interface MemoCache
{
    /**
     * If a cached value exists for the key, return that value.
     * If it doesn't exist, cache and return the result of $callback().
     *
     * enabled = false, always return $callback()
     *
     * @template T
     *
     * @param string $key
     * @param callable(CacheItemInterface):T $callback
     * @param bool $enabled Whether to enable caching or not
     * @return T
     * @throws InvalidArgumentException If the $key string is not a legal value
     */
    public function get(
        string $key,
        callable $callback,
        bool $enabled = true,
    ): mixed;

    /**
     * Clear all cached values.
     *
     * @return void
     */
    public function clear(): void;
}
