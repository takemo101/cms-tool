<?php

use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Tests\TestCase;
use Twig\Environment;

describe(
    'TwigCleanCommand twig:clean',
    function () {
        test(
            'Remove the cache directory by twig:clean command',
            function () {
                /** @var TestCase $this */

                /** @var Environment */
                $twig = $this->getContainer()->get(Environment::class);
                /** @var LocalFilesystem */
                $filesystem = $this->getContainer()->get(LocalFilesystem::class);

                $cache = $twig->getCache();

                if (!$filesystem->exists($cache)) {
                    $filesystem->makeDirectory($cache);
                }

                $tester = $this->command('twig:clean');

                $tester->assertCommandIsSuccessful();

                $this->assertDirectoryDoesNotExist($cache);
            },
        );
    }
)->group('command-twig:clean', 'command');
