<?php

use CmsTool\View\Accessor\DataAccessKey;

describe(
    'DataAccessKey',
    function () {

        it('should create a valid DataAccessKey instance', function () {
            $key = new DataAccessKey('valid_key');

            expect($key)->toBeInstanceOf(DataAccessKey::class);
            expect($key->value)->toBe('valid_key');
        });

        it('should throw an exception for an invalid key', function () {
            expect(
                function () {
                    new DataAccessKey('invalid key');
                }
            )->toThrow('Invalid key: invalid key');
        });

        it('should return the placeholder pattern', function () {
            $key = new DataAccessKey('key_*_pattern');

            expect($key->getPlaceholderPattern())->toBe('/^key_(.+)_pattern$/');
        });

        it('should extract arguments from a matching key', function () {
            $key = new DataAccessKey('key_*_pattern');
            $arguments = $key->extractArguments('key_value_pattern');

            expect($arguments)->toBe(['value']);
        });

        it('should return an empty array for an exact match', function () {
            $key = new DataAccessKey('exact_match');
            $arguments = $key->extractArguments('exact_match');

            expect($arguments)->toBe([]);
        });

        it('should return false for a non-matching key', function () {
            $key = new DataAccessKey('key_*_pattern');
            $arguments = $key->extractArguments('non_matching_key');

            expect($arguments)->toBeFalse();
        });

        it('should convert the key to a string', function () {
            $key = new DataAccessKey('key');

            expect((string) $key)->toBe('key');
        });
    }
)->group('DataAccessKey', 'accessor');
