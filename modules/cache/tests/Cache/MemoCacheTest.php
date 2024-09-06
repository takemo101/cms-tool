<?php

use CmsTool\Cache\PsrMemoCache;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;
use Mockery as m;

describe(
    'MemoCache',
    function () {

        it('should return cached value if enabled and item is hit', function () {
            // Create a mock for CacheItemPoolInterface
            $pool = m::mock(CacheItemPoolInterface::class);
            $item = m::mock(CacheItemInterface::class);

            // Configure the mock
            $pool->shouldReceive('getItem')->andReturn($item);
            $item->shouldReceive('isHit')->andReturn(true);
            $item->shouldReceive('get')->andReturn('cached value');

            // Create an instance of MemoCache
            $cache = new PsrMemoCache($pool);

            // Define the callback function
            $callback = function (CacheItemInterface $item) {
                throw new Exception('This callback should not be called');
            };

            // Call the get method
            $result = $cache->get('key', $callback);

            // Assertions
            expect($result)->toBe('cached value');
        });

        it('should return new value if enabled and item is not hit', function () {
            // Create a mock for CacheItemPoolInterface
            $pool = m::mock(CacheItemPoolInterface::class);
            $item = m::mock(CacheItemInterface::class);

            // Configure the mock
            $pool->shouldReceive('getItem')->andReturn($item);
            $item->shouldReceive('isHit')->andReturn(false);
            $item->shouldReceive('expiresAfter');
            $item->shouldReceive('set');
            $pool->shouldReceive('save');

            // Create an instance of MemoCache
            $cache = new PsrMemoCache($pool);

            // Define the callback function
            $callback = function (CacheItemInterface $item) {
                return 'new value';
            };

            // Call the get method
            $result = $cache->get('key', $callback);

            // Assertions
            expect($result)->toBe('new value');
        });

        it('should always return callback value if cache is disabled', function () {
            // Create a mock for CacheItemPoolInterface
            $pool = m::mock(CacheItemPoolInterface::class);
            $item = m::mock(CacheItemInterface::class);

            // Configure the mock
            $pool->shouldReceive('getItem')->andReturn($item);
            $pool->shouldReceive('save');
            $item->shouldReceive('expiresAfter');
            $item->shouldReceive('set');

            // Create an instance of MemoCache with cache disabled
            $cache = new PsrMemoCache($pool, false);

            // Define the callback function
            $callback = function (CacheItemInterface $item) {
                return 'callback value';
            };

            // Call the get method
            $result = $cache->get('key', $callback);

            // Assertions
            expect($result)->toBe('callback value');
        });

        it(
            'should remove the cache for the specified key',
            function () {
                // Create a mock for CacheItemPoolInterface
                $pool = m::mock(CacheItemPoolInterface::class);

                // Mock the deleteItem method of CacheItemPoolInterface
                $pool->shouldReceive('deleteItem')->once()->with('key')->andReturn(true);

                // Create an instance of MemoCache
                $cache = new PsrMemoCache($pool);

                // Call the forget method
                $result = $cache->forget('key');

                // Assertions
                expect($result)->toBe(true);
            }
        );

        it('should clear the cache', function () {
            // Create a mock for CacheItemPoolInterface
            $pool = m::mock(CacheItemPoolInterface::class);

            // Mock the clear method of CacheItemPoolInterface
            $pool->shouldReceive('clear')->once()->andReturn(true);

            // Create an instance of MemoCache with cache disabled
            $cache = new PsrMemoCache($pool, false);

            expect($cache->clear())->toBe(true);
        });
    }
)->group('MemoCache', 'cache');
