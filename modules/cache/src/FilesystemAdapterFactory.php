<?php

namespace CmsTool\Cache;

use CmsTool\Cache\Contract\CacheAdapterFactory;
use DI\Attribute\Inject;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Marshaller\MarshallerInterface;
use Symfony\Component\Cache\ResettableInterface;
use Symfony\Contracts\Cache\CacheInterface;

class FilesystemAdapterFactory implements CacheAdapterFactory
{
    /**
     * constructor
     *
     * @param MarshallerInterface $marshaller
     * @param LoggerInterface $logger
     * @param string $directory
     * @param int $lifetime
     */
    public function __construct(
        private MarshallerInterface $marshaller,
        private LoggerInterface $logger,
        #[Inject('config.cache.filesystem.directory')]
        private string $directory = '',
        #[Inject('config.cache.lifetime')]
        private int $lifetime = 0,
    ) {
        //
    }

    /**
     * @inheritDoc
     */
    public function create(): AdapterInterface & CacheInterface & ResettableInterface
    {
        $adapter = new FilesystemAdapter(
            defaultLifetime: $this->lifetime,
            directory: $this->directory,
            marshaller: $this->marshaller,
        );

        $adapter->setLogger($this->logger);

        return $adapter;
    }
}
