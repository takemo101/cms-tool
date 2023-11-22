<?php

use CmsTool\View\Twig\Extension\ConfigExtension;
use Takemo101\Chubby\Config\ConfigRepository;
use Tests\TestCase;

describe(
    'ConfigExtension',
    function () {
        test(
            'should register config Twig functions',
            function () {
                /** @var TestCase $this */

                $extension = new ConfigExtension(
                    $this->getContainer()->get(ConfigRepository::class),
                );

                $functions = $extension->getFunctions();

                expect($functions)->toHaveCount(1);

                foreach ($functions as $function) {
                    expect($function->getCallable()[0])->toBeInstanceOf(ConfigRepository::class);
                }
            },
        );
    }
)->group('config-extension', 'extension');
