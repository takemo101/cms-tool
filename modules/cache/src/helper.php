<?php

use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\CallbackInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Takemo101\Chubby\Support\ServiceLocator;

if (!function_exists('cache')) {
    /**
     * Gets an item from the cache or a default value.
     *
     * @template T
     *
     * @param string $key The key of the item to retrieve from the cache
     * @param (callable(CacheItemInterface,bool):T)|(callable(ItemInterface,bool):T)|CallbackInterface<T> $callback
     * @param float|null $beta      A float that, as it grows, controls the likeliness of triggering
     *                              early expiration. 0 disables it, INF forces immediate expiration.
     *                              The default (or providing null) is implementation dependent but should
     *                              typically be 1.0, which should provide optimal stampede protection.
     *                              See https://en.wikipedia.org/wiki/Cache_stampede#Probabilistic_early_expiration
     * @param mixed[]|null      &$metadata The metadata of the cached item {@see ItemInterface::getMetadata()}
     *
     * @return T
     *
     * @throws InvalidArgumentException When $key is not valid or when $beta is negative
     */
    function cache(
        string $key,
        callable $callback,
        float $beta = null,
        ?array &$metadata = null
    ): mixed {
        /** @var CacheInterface */
        $cache = ServiceLocator::container()->get(CacheInterface::class);

        /** @var T */
        $value = $cache->get($key, $callback, $beta, $metadata);

        return $value;
    }
}
