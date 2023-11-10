<?php

use CmsTool\View\Twig\Extension\FunctionsExtension;
use Tests\TestCase;

describe(
    'extension-functions',
    function () {
        test(
            'Register the twig functions',
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
)->group('extension-functions');
