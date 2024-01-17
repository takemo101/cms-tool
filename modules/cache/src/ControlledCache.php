<?php

namespace CmsTool\Cache;

use DI\Attribute\Inject;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;
use InvalidArgumentException;

class ControlledCache
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
     * CacheItemPoolInterface wrapper
     * enabled = false, always return $callback()
     *
     * @template T
     *
     * @param string $key
     * @param callable(CacheItemInterface):T $callback
     * @param bool $enabled default true
     * @return T
     * @throws InvalidArgumentException
     */
    public function get(
        string $key,
        callable $callback,
        bool $enabled = true,
    ): mixed {
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
}
