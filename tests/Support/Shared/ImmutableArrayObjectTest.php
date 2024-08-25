<?php

use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObject;

describe(
    'ImmutableArrayObject',
    function () {
        it('should get the value of a key from the immutable array object using offsetGet', function () {
            $data = ['key1' => 'value1', 'key2' => 'value2', 'key3' => [
                'key4' => 'value4',
                'key5' => [
                    'key6' => 'value6',
                ],
                'key7' => [
                    'data1',
                    'data2',
                    'data3',
                ],
            ]];

            $immutableArray = ImmutableArrayObject::of($data);

            expect($immutableArray->get('key1'))->toBe($data['key1']);
            expect($immutableArray->get('key3.key4'))->toBe($data['key3']['key4']);
            expect($immutableArray->get('key3.key5.key6'))->toBe($data['key3']['key5']['key6']);
            expect($immutableArray->get('key3.key7.0'))->toBe($data['key3']['key7'][0]);
        });

        it('should check if a key exists in the immutable array object using offsetExists', function () {
            $data = ['key1' => 'value1', 'key2' => 'value2', 'key3' => [
                'key4' => 'value4',
                'key5' => [
                    'key6' => 'value6',
                ],
                'key7' => [
                    'data1',
                    'data2',
                    'data3',
                ],
            ]];

            $immutableArray = ImmutableArrayObject::of($data);

            expect(isset($immutableArray['key1']))->toBe(true);
            expect(isset($immutableArray['key3']['key4']))->toBe(true);
            expect(isset($immutableArray['key3']['key5']['key6']))->toBe(true);
            expect(isset($immutableArray['key3']['key7'][0]))->toBe(true);
            expect(isset($immutableArray['nonexistent']))->toBe(false);
        });

        it('should get the default value when the key does not exist in the immutable array object using offsetGet', function () {
            $data = ['key1' => 'value1', 'key2' => 'value2', 'key3' => [
                'key4' => 'value4',
                'key5' => [
                    'key6' => 'value6',
                ],
                'key7' => [
                    'data1',
                    'data2',
                    'data3',
                ],
            ]];

            $immutableArray = ImmutableArrayObject::of($data);

            expect($immutableArray['nonexistent'])->toBe(null);
            expect($immutableArray['key3.nonexistent'])->toBe(null);
            expect($immutableArray['key3.key5.nonexistent'])->toBe(null);
            expect($immutableArray['key3.key7.3'])->toBe(null);
        });

        it('should throw an exception when trying to set a value using offsetSet', function () {
            $immutableArray = ImmutableArrayObject::of();

            expect(function () use ($immutableArray) {
                $immutableArray['key'] = 'value';
            })->toThrow(OutOfBoundsException::class);
        });

        it('should throw an exception when trying to unset a value using offsetUnset', function () {
            $data = ['key1' => 'value1', 'key2' => 'value2', 'key3' => [
                'key4' => 'value4',
                'key5' => 'value5',
            ]];
            $immutableArray = ImmutableArrayObject::of($data);

            expect(function () use ($immutableArray) {
                unset($immutableArray['key1']);
            })->toThrow(OutOfBoundsException::class);
        });

        it('should get the value of a snake case key from the immutable array object using offsetGet', function () {
            $data = [
                'allOf' => 'value',
                'keyOf' => 'value',
            ];

            $immutableArray = ImmutableArrayObject::of($data);

            expect($immutableArray->all_of)->toBe($data['allOf']);
            expect($immutableArray['all_of'])->toBe($data['allOf']);
            expect($immutableArray->key_of)->toBe($data['keyOf']);
            expect($immutableArray['key_of'])->toBe($data['keyOf']);
        });

        it('should get the value of a key from the immutable array object using toArray', function () {
            $data = [
                'key1' => 'value1',
                'key2' => 'value2',
            ];

            $immutableArray = ImmutableArrayObject::of($data);

            expect($immutableArray->toArray())->toBe($data);

            $data = [
                'key1' => 'value1',
                'key2' => ImmutableArrayObject::of([
                    'key3' => 'value3',
                ]),
            ];

            $immutableArray = ImmutableArrayObject::of($data);

            expect($immutableArray->toArray())->toBe([
                ...$data,
                'key2' => $data['key2']->toArray(),
            ]);
        });


        it('should get the value of a key from the immutable array object using jsonSerialize', function () {
            $data = [
                'key1' => 'value1',
                'key2' => 'value2',
            ];

            $immutableArray = ImmutableArrayObject::of($data);

            expect($immutableArray->jsonSerialize())->toBe($data);

            $data = [
                'key1' => 'value1',
                'key2' => ImmutableArrayObject::of([
                    'key3' => 'value3',
                ]),
            ];

            $immutableArray = ImmutableArrayObject::of($data);

            expect($immutableArray->jsonSerialize())->toBe([
                ...$data,
                'key2' => $data['key2']->jsonSerialize(),
            ]);
        });
    }
)->group('ImmutableArrayObject', 'support');
