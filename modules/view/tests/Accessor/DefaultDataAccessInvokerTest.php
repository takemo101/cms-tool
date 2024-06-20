<?php

use CmsTool\View\Accessor\DataAccessInvoker;
use DI\FactoryInterface;
use Mockery as m;

describe(
    'DataAccessInvoker',
    function () {
        beforeEach(function () {
            // Create a mock for the FactoryInterface
            $this->factory = m::mock(FactoryInterface::class);

            // Create a new instance of the DataAccessInvoker class
            $this->invoker = new DataAccessInvoker($this->factory);
        });

        it('invokes a closure accessor', function () {
            $accessor = fn () => 'test';

            $result = $this->invoker->invoke($accessor);

            expect($result)->toBe('test');
        });

        it('invokes a class accessor', function () {
            $accessor = TestAccessor::class;

            $testAccessor = new TestAccessor();

            $this->factory->shouldReceive('make')
                ->with($accessor, [])
                ->andReturn($testAccessor);

            $result = $this->invoker->invoke($accessor);

            expect($result)->toBe($testAccessor());
        });

        it('throws an exception if the accessor class does not exist', function () {
            $accessor = 'NonExistentAccessor';

            $callback = function () use ($accessor) {
                $this->invoker->invoke($accessor);
            };

            expect(fn () => $this->invoker->invoke($accessor))
                ->toThrow(RuntimeException::class);
        });

        it('throws an exception if the accessor class is not callable', function () {
            $accessor = 'CmsTool\View\Accessor\NonCallableAccessor';

            $nonCallableAccessor = new class
            {
                //
            };

            $this->factory->shouldReceive('make')
                ->with($accessor, [])
                ->andReturn($nonCallableAccessor);

            expect(fn () => $this->invoker->invoke($accessor))
                ->toThrow(RuntimeException::class);
        });
    }
)->group('DataAccessInvoker', 'accessor');

class TestAccessor
{
    public function __invoke(): string
    {
        return 'test';
    }
}
