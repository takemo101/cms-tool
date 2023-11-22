<?php

use CmsTool\View\Html\Transformer\BooleanAttrTransformer;

describe(
    'BooleanAttrTransformer',
    function () {

        $transformer = new BooleanAttrTransformer();

        test(
            'should return null if the value is not a boolean',
            function () use ($transformer) {
                $result = $transformer->transform('disabled', 'not a boolean');

                expect($result)->toBeNull();
            }
        );

        test(
            'should return the key if the value is true',
            function () use ($transformer) {
                $result = $transformer->transform('disabled', true);

                expect($result)->toBe('disabled');
            }
        );

        test(
            'should return null if the value is false',
            function () use ($transformer) {
                $result = $transformer->transform('disabled', false);

                expect($result)->toBeNull();
            }
        );
    }
)->group('boolean-attr-transformer', 'transformer');
