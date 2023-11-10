<?php

use CmsTool\View\Twig\Extension\ContextExtension;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Takemo101\Chubby\Http\Context;
use Tests\TestCase;

describe(
    'extension-config',
    function () {
        test(
            'Register the context twig function',
            function () {
                /** @var TestCase $this */

                $extension = new ContextExtension(
                    new Context(
                        request: Mockery::mock(ServerRequestInterface::class),
                        response: Mockery::mock(ResponseInterface::class),
                        routeArguments: [],
                    )
                );
                $functions = $extension->getFunctions();

                expect($functions)->toHaveCount(2);

                foreach ($functions as $function) {
                    expect($function->getCallable()[0])->toBeInstanceOf(ContextExtension::class);
                }
            },
        );
    }
)->group('extension-context');
