<?php

use CmsTool\View\Twig\Extension\RouteExtension;
use Slim\Interfaces\RouteParserInterface;
use Tests\TestCase;

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
                    expect($function->getCallable()[0])->toBeInstanceOf(RouteParserInterface::class);
                }
            },
        );
    }
)->group('route-extension', 'extension');
