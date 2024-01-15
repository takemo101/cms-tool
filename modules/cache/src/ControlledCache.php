<?php

namespace CmsTool\Cache;

use DI\Attribute\Inject;
use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\CallbackInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ControlledCache
{
    /**
     * constructor
     *
     * @param CacheInterface $cache
     * @param boolean $enabled
     */
    public function __construct(
        private readonly CacheInterface $cache,
        #[Inject('config.cache.enabled')]
        private readonly bool $enabled = true,
    ) {
        //
    }

    /**
     * CacheInterface::get() wrapper
     * enabled = false, always return $callback()
     *
     * @template T
     *
     * @param string $key
     * @param (callable(CacheItemInterface,bool):T)|(callable(ItemInterface,bool):T)|CallbackInterface<T> $callback $callback
     * @param float|null $beta
     * @param bool $enabled default true
     * @return T
     */
    public function get(
        string $key,
        callable $callback,
        ?float $beta = null,
        bool $enabled = true,
    ): mixed {
        if ($this->enabled && $enabled) {
            return $this->cache->get($key, $callback, $beta);
        }

        return $callback();
    }
}
