<?php

use CmsTool\Cache\FilesystemAdapterFactory;
use DI\Container;
use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Marshaller\MarshallerInterface;
use Symfony\Component\Cache\ResettableInterface;
use Symfony\Contracts\Cache\CacheInterface;

use Mockery as m;

it('should create a FilesystemAdapter instance', function () {

    // Create a Container builder
    $builder = new ContainerBuilder();

    $builder->useAttributes(true);

    $marshaller = m::mock(MarshallerInterface::class);
    $logger = m::mock(LoggerInterface::class);

    // Register dependencies
    $builder->addDefinitions([
        'config.cache.filesystem.directory' => 'storage/cache/data',
        'config.cache.lifetime' => 3600,
        MarshallerInterface::class => $marshaller,
        LoggerInterface::class => $logger,
    ]);

    // Create a DI container
    $container = $builder->build();

    // Create an instance of the factory
    $factory = $container->get(FilesystemAdapterFactory::class);

    // Call the create method
    $adapter = $factory->create();

    // Assertions
    expect($adapter)->toBeInstanceOf(FilesystemAdapter::class);
    expect($adapter)->toBeInstanceOf(CacheInterface::class);
    expect($adapter)->toBeInstanceOf(ResettableInterface::class);
})->group('FilesystemAdapter', 'cache');
