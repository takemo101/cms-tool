<?php

use CmsTool\View\Html\FormBuilder;
use CmsTool\View\Html\ElementBuilder;
use CmsTool\View\Html\Filter\FormAppendFilter;
use CmsTool\View\Html\Filter\FormAppendFilters;
use Slim\Interfaces\RouteParserInterface;

beforeEach(
    function () {
        $this->routeParser = Mockery::mock(RouteParserInterface::class);

        $builder = new ElementBuilder();
        $appendFilter = new FormAppendFilters();

        $this->formBuilder = new FormBuilder($this->routeParser, $builder, $appendFilter);
    }
);

describe(
    'FormBuilder::buildOpen',
    function () {

        it(
            'should build a form element with default method and action',
            function () {
                $attributes = [];

                $expected = '<form method="GET" action="/">';
                $actual = $this->formBuilder->buildOpen($attributes);

                expect($actual)->toBe($expected);
            }
        );

        it(
            'should build a form element with specified method and action',
            function () {
                $attributes = [
                    'method' => 'POST',
                    'action' => '/submit',
                ];

                $expected = '<form method="POST" action="/submit">';
                $actual = $this->formBuilder->buildOpen($attributes);

                expect($actual)->toBe($expected);
            }
        );

        it(
            'should build a form element with specified route',
            function () {
                $attributes = [
                    'route' => 'my.route',
                ];

                $expectedUrl = '/my/route';

                $this->routeParser
                    ->shouldReceive('urlFor')
                    ->with('my.route')
                    ->andReturn($expectedUrl);

                $expected = '<form method="GET" action="' . $expectedUrl . '">';
                $actual = $this->formBuilder->buildOpen($attributes);

                expect($actual)->toBe($expected);
            }
        );

        it(
            'should build a form element with specified route and data',
            function () {
                $attributes = [
                    'route' => ['my.route', ['id' => 123]],
                ];

                $expectedUrl = '/my/route/123';

                $this->routeParser
                    ->shouldReceive('urlFor')
                    ->with('my.route', ['id' => 123])
                    ->andReturn($expectedUrl);

                $expected = '<form method="GET" action="' . $expectedUrl . '">';
                $actual = $this->formBuilder->buildOpen($attributes);

                expect($actual)->toBe($expected);
            }
        );

        it(
            'should throw an exception if route name is not specified',
            function () {
                $attributes = [
                    'route' => [],
                ];

                expect(fn () => $this->formBuilder->buildOpen($attributes))
                    ->toThrow(InvalidArgumentException::class);
            }
        );

        it(
            'should throw an exception if route data is not an array',
            function () {
                $attributes = [
                    'route' => ['my.route', 'invalid'],
                ];

                expect(fn () => $this->formBuilder->buildOpen($attributes))
                    ->toThrow(InvalidArgumentException::class);
            }
        );

        it(
            'should append form content',
            function () {
                $attributes = [];

                $appendFilter = Mockery::mock(FormAppendFilter::class);

                $appendFilter
                    ->shouldReceive('filter')
                    ->with($attributes)
                    ->andReturn('<input type="hidden" name="token" value="abc123">');

                $formBuilder = new FormBuilder(
                    Mockery::mock(RouteParserInterface::class),
                    new ElementBuilder(),
                    $appendFilter,
                );

                $expected = '<form method="GET" action="/"><input type="hidden" name="token" value="abc123">';
                $actual = $formBuilder->buildOpen($attributes);

                expect($actual)->toBe($expected);
            }
        );
    }
)->group('form-builder', 'html');

describe(
    'FormBuilder::buildClose',
    function () {

        it(
            'should build a form element close tag',
            function () {

                $expected = '</form>';
                $actual = $this->formBuilder->buildClose();

                expect($actual)->toBe($expected);
            }
        );
    }
)->group('form-builder', 'html');
