<?php

namespace Takemo101\CmsTool\Infra\Cache;

use CmsTool\Cache\MemoCacheOptions;
use CmsTool\Cache\PsrMemoCache;
use Psr\Cache\CacheItemInterface;

class PsrApiMemoCache implements ApiMemoCache
{
    /**
     * constructor
     *
     * @param PsrMemoCache $memo
     */
    public function __construct(
        private readonly PsrMemoCache $memo,
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
        return $this->memo->get(
            key: $key,
            callback: $callback,
            options: $options,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function forget(string $key): bool
    {
        return $this->memo->forget($key);
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): bool
    {
        return $this->memo->clear();
    }
}
