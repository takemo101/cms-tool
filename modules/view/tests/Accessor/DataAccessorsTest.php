<?php

use CmsTool\View\Accessor\DataAccessors;
use CmsTool\View\Accessor\DataAccessInvoker;
use Mockery as m;

describe(
    'DataAccessors',
    function () {

        it('returns the value from the accessor', function () {
            $invoker = m::mock(DataAccessInvoker::class);

            $accessors = new DataAccessors($invoker);

            // Define the key and expected value
            $key = 'test_key';
            $expectedValue = 'test_value';

            // Set up the mock invoker to return the expected value
            $invoker->shouldReceive('invoke')
                ->andReturn($expectedValue);

            $accessors->add($key, Closure::fromCallable(fn () => 'test'));

            expect($accessors->get($key))->toBe($expectedValue);
        });

        it('returns the default value if the key is not found', function () {
            $invoker = m::mock(DataAccessInvoker::class);

            $accessors = new DataAccessors($invoker);

            // Define the key and default value
            $key = 'nonexistent_key';
            $defaultValue = 'default_value';

            expect($accessors->get($key, $defaultValue))->toBe($defaultValue);
        });

        it('checks if a value exists for the key', function () {
            $invoker = m::mock(DataAccessInvoker::class);

            $accessors = new DataAccessors($invoker);

            // Define the key
            $key = 'existing_key';

            $accessors->add($key, Closure::fromCallable(fn () => 'test'));

            expect($accessors->has($key))->toBeTrue();
        });

        it('checks if a value does not exist for the key', function () {
            $invoker = m::mock(DataAccessInvoker::class);

            $accessors = new DataAccessors($invoker);

            // Define the key
            $key = 'nonexistent_key';

            expect($accessors->has($key))->toBeFalse();
        });

        it('clears the cache', function () {
            $invoker = m::mock(DataAccessInvoker::class);

            $accessors = new DataAccessors($invoker);

            // Define a key and value to add to the cache
            $key = 'test_key';
            $value = 'test_value';
            $accessors->get($key, $value);

            $accessors->clearCache();

            expect($accessors->has($key))->toBeFalse();
        });
    }
)->group('DataAccessors', 'accessor');
