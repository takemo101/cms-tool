<?php

use CmsTool\View\Accessor\DataAccessKeys;
use CmsTool\View\Accessor\DataAccessKey;

describe(
    'DataAccessKeys',
    function () {

        it('should create a valid DataAccessKeys instance', function () {
            $keys = new DataAccessKeys(
                new DataAccessKey('key1'),
                new DataAccessKey('key2'),
                new DataAccessKey('key3')
            );

            expect($keys)->toBeInstanceOf(DataAccessKeys::class);
            expect($keys->keys)->toBeArray();
            expect(count($keys->keys))->toBe(3);
        });

        it('should extract arguments from a matching key', function () {
            $keys = new DataAccessKeys(
                new DataAccessKey('key1_*_pattern'),
                new DataAccessKey('key2_*_pattern'),
                new DataAccessKey('key3_*_pattern')
            );

            $arguments = $keys->extractArguments('key2_value_pattern');

            expect($arguments)->toBe(['value']);
        });

        it('should return false for a non-matching key', function () {
            $keys = new DataAccessKeys(
                new DataAccessKey('key1_*_pattern'),
                new DataAccessKey('key2_*_pattern'),
                new DataAccessKey('key3_*_pattern')
            );

            $arguments = $keys->extractArguments('non_matching_key');

            expect($arguments)->toBeFalse();
        });

        it('should create an instance from an array of strings', function () {
            $keys = DataAccessKeys::fromStrings('key1', 'key2', 'key3');

            expect($keys)->toBeInstanceOf(DataAccessKeys::class);
            expect($keys->keys)->toBeArray();
            expect(count($keys->keys))->toBe(3);
        });

        it('should remove duplicate keys when creating an instance from an array of strings', function () {
            $keys = DataAccessKeys::fromStrings('key1', 'key2', 'key2', 'key3', 'key3');

            expect($keys)->toBeInstanceOf(DataAccessKeys::class);
            expect($keys->keys)->toBeArray();
            expect(count($keys->keys))->toBe(3);
        });
    }
)->group('DataAccessKeys', 'accessor');
