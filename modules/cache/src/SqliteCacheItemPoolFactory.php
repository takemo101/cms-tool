<?php

namespace CmsTool\Cache;

use CmsTool\Cache\Contract\CacheItemPoolFactory;
use DI\Attribute\Inject;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Stash\Driver\Sqlite;
use Stash\Pool;

class SqliteCacheItemPoolFactory implements CacheItemPoolFactory
{
    /**
     * constructor
     *
     * @param LoggerInterface $logger
     * @param array<string,mixed> $options
     */
    public function __construct(
        private LoggerInterface $logger,
        #[Inject('config.cache.sqlite')]
        private array $options = [],
    ) {
        //
    }

    /**
     * @return CacheItemPoolInterface
     */
    public function create(): CacheItemPoolInterface
    {
        $driver = new Sqlite($this->options);

        $pool = new Pool($driver);

        $pool->setLogger($this->logger);

        return $pool;
    }
}
