<?php

use CmsTool\View\Accessor\DataAccessAdapter;
use CmsTool\View\Accessor\DataAccessors;
use Mockery as m;

describe(
    'DataAccessAdapter',
    function () {

        it('returns true if the offset exists in the accessors', function () {
            // Create a mock for the DataAccessors class
            $accessors = m::mock(DataAccessors::class);

            // Set up the mock accessors to return true for offsetExists
            $accessors->shouldReceive('has')
                ->with('test_offset')
                ->andReturn(true);

            // Create a new instance of the DataAccessAdapter class
            $adapter = new DataAccessAdapter($accessors);

            // Call the offsetExists method with a valid offset and assert true
            expect($adapter->offsetExists('test_offset'))->toBeTrue();
        });

        it('returns false if the offset does not exist in the accessors', function () {
            // Create a mock for the DataAccessors class
            $accessors = m::mock(DataAccessors::class);

            // Set up the mock accessors to return false for offsetExists
            $accessors->shouldReceive('has')
                ->with('nonexistent_offset')
                ->andReturn(false);

            // Create a new instance of the DataAccessAdapter class
            $adapter = new DataAccessAdapter($accessors);

            // Call the offsetExists method with a nonexistent offset and assert false
            expect($adapter->offsetExists('nonexistent_offset'))->toBeFalse();
        });

        it('returns the value from the accessors for a valid offset', function () {
            // Create a mock for the DataAccessors class
            $accessors = m::mock(DataAccessors::class);

            // Set up the mock accessors to return a value for offsetGet
            $accessors->shouldReceive('get')
                ->with('test_offset')
                ->andReturn('test_value');

            // Create a new instance of the DataAccessAdapter class
            $adapter = new DataAccessAdapter($accessors);

            // Call the offsetGet method with a valid offset and assert the returned value
            expect($adapter->offsetGet('test_offset'))->toBe('test_value');
        });

        it('returns null for an invalid offset', function () {
            // Create a mock for the DataAccessors class
            $accessors = m::mock(DataAccessors::class);

            // Create a new instance of the DataAccessAdapter class
            $adapter = new DataAccessAdapter($accessors);

            // Call the offsetGet method with an invalid offset and assert null
            expect($adapter->offsetGet(123))->toBeNull();
        });

        it('throws an exception when trying to set an offset', function () {
            // Create a mock for the DataAccessors class
            $accessors = m::mock(DataAccessors::class);

            // Create a new instance of the DataAccessAdapter class
            $adapter = new DataAccessAdapter($accessors);

            // Call the offsetSet method and assert that it throws a RuntimeException
            expect(fn () => $adapter->offsetSet('test_offset', 'test_value'))
                ->toThrow(RuntimeException::class);
        });

        it('throws an exception when trying to unset an offset', function () {
            // Create a mock for the DataAccessors class
            $accessors = m::mock(DataAccessors::class);

            // Create a new instance of the DataAccessAdapter class
            $adapter = new DataAccessAdapter($accessors);

            // Call the offsetUnset method and assert that it throws a RuntimeException
            expect(fn () => $adapter->offsetUnset('test_offset'))
                ->toThrow(RuntimeException::class);
        });

        it('returns the value from the accessors using magic __get method', function () {
            // Create a mock for the DataAccessors class
            $accessors = m::mock(DataAccessors::class);

            // Set up the mock accessors to return a value for offsetGet
            $accessors->shouldReceive('get')
                ->with('test_offset')
                ->andReturn('test_value');

            // Create a new instance of the DataAccessAdapter class
            $adapter = new DataAccessAdapter($accessors);

            // Call the magic __get method with a valid offset and assert the returned value
            expect($adapter->__get('test_offset'))->toBe('test_value');
        });

        it('returns true if the offset exists using magic __isset method', function () {
            // Create a mock for the DataAccessors class
            $accessors = m::mock(DataAccessors::class);

            // Set up the mock accessors to return true for offsetExists
            $accessors->shouldReceive('has')
                ->with('test_offset')
                ->andReturn(true);

            // Create a new instance of the DataAccessAdapter class
            $adapter = new DataAccessAdapter($accessors);

            // Call the magic __isset method with a valid offset and assert true
            expect($adapter->__isset('test_offset'))->toBeTrue();
        });

        it('returns false if the offset does not exist using magic __isset method', function () {
            // Create a mock for the DataAccessors class
            $accessors = m::mock(DataAccessors::class);

            // Set up the mock accessors to return false for offsetExists
            $accessors->shouldReceive('has')
                ->with('nonexistent_offset')
                ->andReturn(false);

            // Create a new instance of the DataAccessAdapter class
            $adapter = new DataAccessAdapter($accessors);

            // Call the magic __isset method with a nonexistent offset and assert false
            expect($adapter->__isset('nonexistent_offset'))->toBeFalse();
        });
    }
)->group('DataAccessAdapter', 'accessor');
