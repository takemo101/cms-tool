<?php

use CmsTool\View\Html\Transformer\AttrTransformers;
use CmsTool\View\Html\Transformer\AttrTransformer;

describe(
    'AttrTransformers',
    function () {

        $transformers = new AttrTransformers(
            new class implements AttrTransformer
            {
                public function transform(string $key, mixed $value): ?string
                {
                    return $value === true ? $key : null;
                }
            },
        );

        it(
            'should return the key if the value is null',
            function () use ($transformers) {
                $result = $transformers->transform('disabled', null);

                expect($result)->toBe('disabled');
            }
        );

        it(
            'should return the key-value pair if the value is a string',
            function () use ($transformers) {
                $result = $transformers->transform('class', 'my-class');

                expect($result)->toBe('class="my-class"');
            }
        );

        it(
            'should return the key-value pair if the value is numeric',
            function () use ($transformers) {
                $result = $transformers->transform('data-id', 123);

                expect($result)->toBe('data-id="123"');
            }
        );

        it(
            'should return the key-value pair if the value is a Stringable object',
            function () use ($transformers) {
                $value = new class implements Stringable
                {
                    public function __toString(): string
                    {
                        return 'my-value';
                    }
                };

                $result = $transformers->transform('data-value', $value);

                expect($result)->toBe('data-value="my-value"');
            }
        );

        it(
            'should return the result of the first transformer that returns a non-null value',
            function () use ($transformers) {
                $result = $transformers->transform('disabled', true);

                expect($result)->toBe('disabled');
            }
        );

        it(
            'should return the value if no transformer returns a null value',
            function () use ($transformers) {
                $value = new stdClass();
                $result = $transformers->transform('data-obj', $value);

                expect($result)->toBeNull();
            }
        );
    }
)->group('attr-transformers', 'transformer');
