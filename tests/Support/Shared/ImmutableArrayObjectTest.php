<?php

use Takemo101\CmsTool\Support\Shared\ImmutableArrayObject;

describe(
    'ImmutableArrayObject',
    function () {
        it('should create an immutable array object', function () {
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

            expect($immutableArray->toArray())->toBe($data);
            expect($immutableArray->key3->toArray())->toBe($data['key3']);
            expect($immutableArray->key3->key4)->toBe($data['key3']['key4']);
            expect($immutableArray->key3->key5['key6'])->toBe($data['key3']['key5']['key6']);
            expect($immutableArray->key3->key7[0])->toBe($data['key3']['key7'][0]);
        });

        it('should throw an exception when trying to set a value', function () {
            $immutableArray = ImmutableArrayObject::of();

            expect(function () use ($immutableArray) {
                $immutableArray->key = 'value';
            })->toThrow(OutOfBoundsException::class);
        });

        it('should throw an exception when trying to unset a value', function () {
            $data = ['key1' => 'value1', 'key2' => 'value2', 'key3' => [
                'key4' => 'value4',
                'key5' => 'value5',
            ]];
            $immutableArray = ImmutableArrayObject::of($data);

            expect(function () use ($immutableArray) {
                unset($immutableArray['key1']);
            })->toThrow(OutOfBoundsException::class);
        });

        it('should get the value of a key from the immutable array object', function () {
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

        it('should get the default value when the key does not exist in the immutable array object', function () {
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

            expect($immutableArray->get('key8', 'default'))->toBe('default');
            expect($immutableArray->get('key3.key8', 'default'))->toBe('default');
            expect($immutableArray->get('key3.key5.key8', 'default'))->toBe('default');
            expect($immutableArray->get('key3.key7.3', 'default'))->toBe('default');
        });
    }
)->group('ImmutableArrayObject', 'support');
