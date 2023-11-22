<?php

use CmsTool\Support\JsonAccess\JsonAccessObject;
use CmsTool\Support\JsonAccess\JsonArraySaver;
use Takemo101\Chubby\Contract\Arrayable;

beforeEach(function () {
    $this->saver = Mockery::mock(JsonArraySaver::class);
    $this->path = '/path/to/test.json';
    $this->data = [
        'foo' => 'bar',
        'baz' => 123,
        'qux' => [
            'quux' => true,
            'corge' => null,
        ],
    ];
    $this->object = new JsonAccessObject($this->saver, $this->path, $this->data);
});

describe(
    'JsonAccessObject::all',
    function () {

        test(
            'should return all data',
            function () {
                expect($this->object->all())->toBe($this->data);
            }
        );
    }
)->group('json-access-object', 'json-access');

describe(
    'JsonAccessObject::get',
    function () {

        test(
            'should return the data of the specified key',
            function () {
                expect($this->object->get('foo'))->toBe('bar');
                expect($this->object->get('baz'))->toBe(123);
                expect($this->object->get('qux'))->toBe($this->data['qux']);
            }
        );

        test(
            'should return the default value if the key does not exist',
            function () {
                expect($this->object->get('nonexistent', 'default'))->toBe('default');
            }
        );
    }
)->group('json-access-object', 'json-access');

describe(
    'JsonAccessObject::set',
    function () {

        test(
            'should set the data on the specified key',
            function () {
                $this->object->set('foo', 'new value');
                expect($this->object->get('foo'))->toBe('new value');

                $this->object->set('new key', 'new value');
                expect($this->object->get('new key'))->toBe('new value');
            }
        );
    }
)->group('json-access-object', 'json-access');

describe(
    'json-access-oject::delete',
    function () {

        test(
            'should delete the data of the specified key',
            function () {
                $this->object->delete('foo');
                expect($this->object->has('foo'))->toBeFalse();
                expect($this->object->get('foo'))->toBeNull();
            }
        );
    }
)->group('json-access-object', 'json-access');

describe(
    'json-access-oject::has',
    function () {

        test(
            'should return true if the specified key exists',
            function () {
                expect($this->object->has('foo'))->toBeTrue();
                expect($this->object->has('baz'))->toBeTrue();
                expect($this->object->has('qux'))->toBeTrue();
            }
        );

        test(
            'should return false if the specified key does not exist',
            function () {
                expect($this->object->has('nonexistent'))->toBeFalse();
            }
        );
    }
)->group('json-access-object', 'json-access');

describe(
    'jsonAccessObject::save',
    function () {

        test(
            'should save the current data in JSON format',
            function () {
                $this->saver->shouldReceive('save')
                    ->once()
                    ->with($this->path, $this->data)
                    ->andReturnNull();

                $this->object->save();
            }
        );
    }
)->group('json-access-object', 'json-access');

describe(
    'JsonAccessObject::toArray',
    function () {

        test(
            'should implement Arrayable',
            function () {
                expect($this->object)->toBeInstanceOf(Arrayable::class);
            }
        );

        test(
            'should convert the object to its array representation',
            function () {
                expect($this->object->toArray())->toBe($this->data);
            }
        );
    }
)->group('json-access-object', 'json-access');
