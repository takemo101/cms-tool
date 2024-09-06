<?php

namespace CmsTool\Cache\Contract;

use CmsTool\Cache\MemoCacheOptions;
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
     * options.enabled = false, always return $callback()
     *
     * @template T
     *
     * @param string $key
     * @param callable(CacheItemInterface):T $callback
     * @param MemoCacheOptions|null $options Cache options
     * @return T
     * @throws InvalidArgumentException If the $key string is not a legal value
     */
    public function get(
        string $key,
        callable $callback,
        ?MemoCacheOptions $options = null,
    ): mixed;
}
