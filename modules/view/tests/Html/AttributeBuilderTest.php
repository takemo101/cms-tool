<?php

use CmsTool\View\Html\AttributeBuilder;
use CmsTool\View\Html\Transformer\AttrTransformer;
use CmsTool\View\Html\Transformer\AttrTransformers;

describe(
    'AttributeBuilder',
    function () {

        $builder = new AttributeBuilder(new AttrTransformers());

        it(
            'should return an empty string when given an empty array',
            function () use ($builder) {
                $result = $builder->build([]);

                expect($result)->toBe('');
            }
        );

        it(
            'should return a string of attributes when given an array of attributes',
            function () use ($builder) {
                $attributes = [
                    'class' => 'foo bar',
                    'id' => 'baz',
                    'data-foo' => 'bar',
                    'data-bar' => null,
                    'data-baz' => '',
                    'data-qux' => 0,
                ];

                $actual = $builder->build($attributes);

                expect($actual)->toBe('class="foo bar" id="baz" data-foo="bar" data-bar data-baz="" data-qux="0"');
            }
        );

        it(
            'should transform attribute keys to lowercase',
            function () use ($builder) {
                $attributes = [
                    'CLASS' => 'foo bar',
                    'ID' => 'baz',
                    'DATA-FOO' => 'bar',
                ];

                $result = $builder->build($attributes);

                expect($result)->toBe('class="foo bar" id="baz" data-foo="bar"');
            }
        );
    }
)->group('attribute-builder', 'html');
