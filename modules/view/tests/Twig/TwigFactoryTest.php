
<?php

use CmsTool\View\Twig\TwigFactory;
use CmsTool\View\Twig\TwigOption;
use Tests\View\TestCase;
use Twig\Environment;
use Twig\Loader\LoaderInterface;

describe(
    'TwigFactory',
    function () {
        test(
            'Create an Environment instance',
            function () {
                /** @var TestCase $this */

                $factory = new TwigFactory(
                    $this->getContainer(),
                    Mockery::mock(LoaderInterface::class),
                    new TwigOption(cache: '/tmp'),
                    safeClasses: [],
                    extensions: [],
                );

                $twig = $factory->create();

                expect($twig)->toBeInstanceOf(Environment::class);
            },
        );
    }
)->group('twig-factory', 'twig');
