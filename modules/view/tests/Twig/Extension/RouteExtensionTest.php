<?php

use CmsTool\View\Twig\Extension\RouteExtension;
use Slim\Interfaces\RouteParserInterface;
use Tests\View\TestCase;

describe(
    'RouteExtension',
    function () {
        it(
            'should register route Twig functions',
            function () {
                /** @var TestCase $this */

                $functions = [
                    'array_filter',
                ];

                $extension = new RouteExtension(
                    Mockery::mock(RouteParserInterface::class),
                );

                $functions = $extension->getFunctions();

                expect($functions)->toHaveCount(1);

                foreach ($functions as $function) {
                    expect($function->getCallable())->toBeInstanceOf(Closure::class);
                }
            },
        );
    }
)->group('route-extension', 'extension');
