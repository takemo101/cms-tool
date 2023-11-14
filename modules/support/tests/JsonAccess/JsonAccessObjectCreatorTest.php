<?php

use CmsTool\Support\JsonAccess\JsonAccessObject;
use CmsTool\Support\JsonAccess\JsonAccessObjectCreator;
use CmsTool\Support\JsonAccess\JsonArrayAccessor;

beforeEach(function () {
    $this->accessor = Mockery::mock(JsonArrayAccessor::class);
    $this->creator = new JsonAccessObjectCreator($this->accessor);
});

describe(
    'json-access-object-creator::create',
    function () {

        test(
            'should return a new JsonAccessObject if it does not exist',
            function () {
                $path = '/path/to/test.json';
                $default = ['foo' => 'bar'];

                $this->accessor->shouldReceive('exists')
                    ->once()
                    ->with($path)
                    ->andReturn(false);

                $this->accessor->shouldReceive('load')
                    ->never();

                $object = $this->creator->create($path, $default);

                expect($object)->toBeInstanceOf(JsonAccessObject::class);
                expect($object->toArray())->toBe($default);
            }
        );

        test(
            'should return a new JsonAccessObject if it does not exist and no default is provided',
            function () {
                $path = '/path/to/test.json';

                $this->accessor->shouldReceive('exists')
                    ->once()
                    ->with($path)
                    ->andReturn(false);

                $this->accessor->shouldReceive('load')
                    ->never();

                $object = $this->creator->create($path);

                expect($object)->toBeInstanceOf(JsonAccessObject::class);
                expect($object->toArray())->toBe([]);
            }
        );

        test(
            'should return an existing JsonAccessObject if it exists',
            function () {
                $path = '/path/to/test.json';
                $data = ['baz' => 'qux'];

                $this->accessor->shouldReceive('exists')
                    ->once()
                    ->with($path)
                    ->andReturn(true);

                $this->accessor->shouldReceive('load')
                    ->once()
                    ->with($path)
                    ->andReturn($data);

                $object = $this->creator->create($path);

                expect($object)->toBeInstanceOf(JsonAccessObject::class);
                expect($object->toArray())->toBe($data);
            }
        );

        test(
            'should return the same JsonAccessObject for the same path',
            function () {
                $path = '/path/to/test.json';
                $data = ['baz' => 'qux'];

                $this->accessor->shouldReceive('exists')
                    ->once()
                    ->with($path)
                    ->andReturn(true);

                $this->accessor->shouldReceive('load')
                    ->once()
                    ->with($path)
                    ->andReturn($data);

                $object1 = $this->creator->create($path);
                $object2 = $this->creator->create($path);

                expect($object1)->toBe($object2);
            }
        );
    }
)->group('json-access-object-creator', 'json-access');
