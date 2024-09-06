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
     * @param int<1,max> $lifetime
     */
    public function __construct(
        private readonly CacheItemPoolInterface $pool,
        #[Inject('config.cache.enabled')]
        private readonly bool $enabled = true,
        #[Inject('config.cache.lifetime')]
        private readonly int $lifetime = 21600,
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
     * @param MemoCacheOptions|null $options Cache options
     * @return T
     */
    public function get(
        string $key,
        callable $callback,
        ?MemoCacheOptions $options = null,
    ): mixed {
        // Use default values if options are not specified.
        $enabled = $options?->enabled ?? $this->enabled;

        $item = $this->pool->getItem($key);

        if ($enabled && $item->isHit()) {
            /** @var T */
            $value = $item->get();

            return $value;
        }

        $item->expiresAfter(
            $options?->lifetime ?? $this->lifetime,
        );

        /** @var T */
        $value = call_user_func($callback, $item);

        $item->set($value);
        $this->pool->save($item);

        return $value;
    }

    /**
     * Clear all cache items.
     *
     * @return boolean If the cache was cleared successfully, it returns true.
     */
    public function clear(): bool
    {
        return $this->pool->clear();
    }
}
