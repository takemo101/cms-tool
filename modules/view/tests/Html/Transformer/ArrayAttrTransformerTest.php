<?php

use CmsTool\View\Html\Transformer\ArrayAttrTransformer;

describe(
    'ArrayAttrTransformer',
    function () {

        $transformer = new ArrayAttrTransformer();

        it(
            'should return null if the value is not an array',
            function () use ($transformer) {
                $result = $transformer->transform('class', 'not an array');

                expect($result)->toBeNull();
            }
        );

        it(
            'should return null if the value is an empty array',
            function () use ($transformer) {
                $result = $transformer->transform('class', []);

                expect($result)->toBeNull();
            }
        );

        it(
            'should return a string of space-separated values if the value is a non-empty array of strings',
            function () use ($transformer) {
                $result = $transformer->transform('class', ['foo', 'bar', 'baz']);

                expect($result)->toBe('class="foo bar baz"');
            }
        );

        it(
            'should ignore non-string values in the array',
            function () use ($transformer) {
                $result = $transformer->transform('class', ['foo', 123, true, 'bar', null, 'baz']);

                expect($result)->toBe('class="foo 123 true bar baz"');
            }
        );

        it(
            'should ignore empty strings in the array',
            function () use ($transformer) {
                $result = $transformer->transform('class', ['foo', '', 'bar', 'baz', '']);

                expect($result)->toBe('class="foo bar baz"');
            }
        );
    }
)->group('array-attr-transformer', 'transformer');
