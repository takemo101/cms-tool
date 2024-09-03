<?php

namespace CmsTool\Cache;

use CmsTool\Cache\Contract\MemoCache;
use DI\Attribute\Inject;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;

class PsrMemoCache implements MemoCache
{
    /**
     * constructor
     *
     * @param CacheItemPoolInterface $pool
     * @param boolean $enabled
     * @param integer $lifetime
     */
    public function __construct(
        private readonly CacheItemPoolInterface $pool,
        #[Inject('config.cache.enabled')]
        private readonly bool $enabled = true,
        #[Inject('config.cache.lifetime')]
        private readonly int $lifetime = 0,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     *
     * @template T
     *
     * @param string $key
     * @param callable(CacheItemInterface):T $callback
     * @param bool $enabled Whether to enable caching or not
     * @return T
     */
    public function get(
        string $key,
        callable $callback,
        bool $enabled = true,
    ): mixed {
        // If the base constructor flag is enabled, then enable the cache.
        // Otherwise, enable the cache based on the argument flag.
        $enabled = $this->enabled && $enabled;

        $item = $this->pool->getItem($key);

        if ($enabled && $item->isHit()) {
            /** @var T */
            $value = $item->get();

            return $value;
        }

        $item->expiresAfter($this->lifetime);

        /** @var T */
        $value = call_user_func($callback, $item);

        $item->set($value);
        $this->pool->save($item);

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): void
    {
        $this->pool->clear();
    }
}
