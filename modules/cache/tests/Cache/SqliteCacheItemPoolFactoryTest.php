<?php

use CmsTool\Cache\SqliteCacheItemPoolFactory;
use DI\ContainerBuilder;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Stash\Driver\Sqlite;
use Stash\Interfaces\PoolInterface;
use Mockery as m;

it('should create a CacheItemPoolInterface instance', function () {

    // Create a Container builder
    $builder = new ContainerBuilder();

    $builder->useAttributes(true);

    $logger = m::mock(LoggerInterface::class);
    $options = ['path' => 'storage/cache/data'];
    $lifetime = 3600;

    // Register dependencies
    $builder->addDefinitions([
        LoggerInterface::class => $logger,
        'config.cache.sqlite' => $options,
        'config.cache.lifetime' => $lifetime,
    ]);

    // Create a DI container
    $container = $builder->build();

    // Create an instance of the factory
    $factory = $container->get(SqliteCacheItemPoolFactory::class);

    // Call the create method
    /** @var PoolInterface */
    $pool = $factory->create();

    // Assertions
    expect($pool)->toBeInstanceOf(CacheItemPoolInterface::class);
    expect($pool->getDriver())->toBeInstanceOf(Sqlite::class);
})->group('SqliteCacheItemPoolFactory', 'cache');
