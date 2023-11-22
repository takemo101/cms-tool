<?php

use CmsTool\View\Html\Transformer\BooleanAttrTransformer;

describe(
    'BooleanAttrTransformer',
    function () {

        $transformer = new BooleanAttrTransformer();

        it(
            'should return null if the value is not a boolean',
            function () use ($transformer) {
                $result = $transformer->transform('disabled', 'not a boolean');

                expect($result)->toBeNull();
            }
        );

        it(
            'should return the key if the value is true',
            function () use ($transformer) {
                $result = $transformer->transform('disabled', true);

                expect($result)->toBe('disabled');
            }
        );

        it(
            'should return null if the value is false',
            function () use ($transformer) {
                $result = $transformer->transform('disabled', false);

                expect($result)->toBeNull();
            }
        );
    }
)->group('boolean-attr-transformer', 'transformer');
