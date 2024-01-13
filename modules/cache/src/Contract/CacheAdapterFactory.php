<?php

namespace CmsTool\Cache\Contract;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\ResettableInterface;
use Symfony\Contracts\Cache\CacheInterface;

interface CacheAdapterFactory
{
    /**
     * @return AdapterInterface&CacheInterface&ResettableInterface
     */
    public function create(): AdapterInterface & CacheInterface & ResettableInterface;
}
