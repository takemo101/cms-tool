<?php

use CmsTool\View\Twig\Extension\RequestExtension;
use Psr\Http\Message\ServerRequestInterface;
use Tests\View\TestCase;

describe(
    'RequestExtension',
    function () {
        it(
            'should register request Twig functions',
            function () {
                /** @var TestCase $this */

                $extension = new RequestExtension(
                    Mockery::mock(ServerRequestInterface::class)
                );
                $functions = $extension->getFunctions();

                expect($functions)->toHaveCount(2);

                foreach ($functions as $function) {
                    expect($function->getCallable())->toBeInstanceOf(Closure::class);
                }
            },
        );
    }
)->group('request-extension', 'extension');
