<?php

use CmsTool\View\Twig\Extension\FunctionsExtension;
use Tests\View\TestCase;

describe(
    'FunctionsExtension',
    function () {
        it(
            'should register functions Twig functions',
            function () {
                /** @var TestCase $this */

                $functions = [
                    'array_filter',
                ];

                $extension = new FunctionsExtension($functions);

                $actualFunctions = $extension->getFunctions();

                expect($actualFunctions)->toHaveCount(count($functions));
            },
        );
    }
)->group('functions-extension', 'extension');
