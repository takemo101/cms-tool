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
     * @param string $directory
     * @param int $lifetime
     * @param MarshallerInterface $marshaller
     * @param LoggerInterface $logger
     */
    public function __construct(
        #[Inject('config.cache.filesystem.directory')]
        private string $directory = '',
        #[Inject('config.cache.lifetime')]
        private int $lifetime = 0,
        private MarshallerInterface $marshaller,
        private LoggerInterface $logger,
    ) {
        //
    }

    /**
     * @inheritDoc
     */
    public function create(): AdapterInterface & CacheInterface & ResettableInterface
    {
        $adapter = new FilesystemAdapter(
            directory: $this->directory,
            defaultLifetime: $this->lifetime,
            marshaller: $this->marshaller,
        );

        $adapter->setLogger($this->logger);

        return $adapter;
    }
}
