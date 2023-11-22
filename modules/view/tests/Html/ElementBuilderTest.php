<?php

use CmsTool\View\Html\AttributeBuilder;
use CmsTool\View\Html\ElementBuilder;

describe(
    'ElementBuilder',
    function () {

        $builder = new ElementBuilder(new AttributeBuilder());

        test(
            'should build an element with no attributes or content',
            function () use ($builder) {
                $result = $builder->buildOpen('div');

                expect($result)->toBe('<div>');
            }
        );

        test(
            'should build an element with attributes but no content',
            function () use ($builder) {
                $attributes = [
                    'class' => 'foo bar',
                    'id' => 'baz',
                ];

                $result = $builder->buildOpen('div', $attributes);

                expect($result)->toBe('<div class="foo bar" id="baz">');
            }
        );

        test(
            'should build an element with content but no attributes',
            function () use ($builder) {
                $result = $builder->build('div', [], 'Hello, world!');

                expect($result)->toBe('<div>Hello, world!</div>');
            }
        );

        test(
            'should build an element with both attributes and content',
            function () use ($builder) {
                $attributes = [
                    'class' => 'foo bar',
                    'id' => 'baz',
                ];

                $result = $builder->build('div', $attributes, 'Hello, world!');

                expect($result)->toBe('<div class="foo bar" id="baz">Hello, world!</div>');
            }
        );

        test(
            'should build a self-terminating element',
            function () use ($builder) {
                $attributes = [
                    'class' => 'foo bar',
                    'id' => 'baz',
                ];

                $result = $builder->build('img', $attributes);

                expect($result)->toBe('<img class="foo bar" id="baz" />');
            }
        );
    }
)->group('element-builder', 'html');
