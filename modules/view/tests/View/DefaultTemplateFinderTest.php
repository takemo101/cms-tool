<?php

use CmsTool\View\DefaultTemplateFinder;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Filesystem\PathHelper;
use Tests\TestCase;

describe(
    'DefaultTemplateFinder',
    function () {
        test(
            'Get path from template name',
            function () {
                /** @var TestCase $this */

                $templateLocation = dirname(__DIR__, 1) . '/resources/views';

                $finder = new DefaultTemplateFinder(
                    $this->getContainer()->get(LocalFilesystem::class),
                    new PathHelper(),
                    [],
                    [],
                    ['twig'],
                );

                $finder->addLocation($templateLocation);

                $actualPath = $finder->find('test');

                expect($actualPath)->toContain($templateLocation);
            },
        );

        test(
            'Get path from template name including namespace',
            function () {
                /** @var TestCase $this */

                $templateLocation = dirname(__DIR__, 1) . '/resources/views';

                $finder = new DefaultTemplateFinder(
                    $this->getContainer()->get(LocalFilesystem::class),
                    new PathHelper(),
                    [],
                    [],
                    ['twig'],
                );

                $namespace = 'test';

                $finder->addNamespace($namespace, $templateLocation);

                $actualPath = $finder->find($namespace . '::test');

                expect($actualPath)->toContain($templateLocation);
            },
        );
    }
)->group('default-template-finder', 'view');
