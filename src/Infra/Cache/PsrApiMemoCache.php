<?php

namespace Takemo101\CmsTool\Infra\Cache;

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
     * @param bool $enabled Whether to enable caching or not
     * @return T
     */
    public function get(
        string $key,
        callable $callback,
        bool $enabled = true,
    ): mixed {
        return $this->memo->get(
            key: $key,
            callback: $callback,
            enabled: $enabled,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): bool
    {
        return $this->memo->clear();
    }
}
