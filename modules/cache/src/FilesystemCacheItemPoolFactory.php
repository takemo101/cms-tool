<?php

namespace CmsTool\Cache;

use CmsTool\Cache\Contract\CacheItemPoolFactory;
use DI\Attribute\Inject;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Stash\Driver\FileSystem;
use Stash\Pool;

class FilesystemCacheItemPoolFactory implements CacheItemPoolFactory
{
    /**
     * constructor
     *
     * @param LoggerInterface $logger
     * @param array<string,mixed> $options
     */
    public function __construct(
        private LoggerInterface $logger,
        #[Inject('config.cache.filesystem')]
        private array $options = [],
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $options = []): CacheItemPoolInterface
    {
        $driver = new FileSystem([
            ...$this->options,
            ...$options,
        ]);

        $pool = new Pool($driver);

        $pool->setLogger($this->logger);

        return $pool;
    }
}
