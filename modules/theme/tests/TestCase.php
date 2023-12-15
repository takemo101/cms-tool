<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Takemo101\Chubby\Application;
use Takemo101\Chubby\ApplicationBuilder;
use Takemo101\Chubby\ApplicationOption;
use Takemo101\Chubby\Test\HasConsoleTest;
use Takemo101\Chubby\Test\HasContainerTest;

class TestCase extends BaseTestCase
{
    use HasContainerTest;
    use HasConsoleTest;

    /**
     * Before each test.
     */
    protected function setUp(): void
    {
        /** @var Application */
        $app = ApplicationBuilder::fromOption(
            ApplicationOption::from(
                basePath: dirname(__DIR__),
            ),
        )
            ->addHttp()
            ->addConsole()
            ->getApplication();

        $this->setUpContainer($app);
        $this->setUpConsole();
    }
}
