<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Takemo101\Chubby\Application;
use Takemo101\Chubby\Test\HasConsoleTest;
use Takemo101\Chubby\Test\HasContainerTest;
use Takemo101\Chubby\Test\HasHttpTest;

class TestCase extends BaseTestCase
{
    use HasContainerTest;
    use HasHttpTest;
    use HasConsoleTest;

    /**
     * Before each test.
     */
    protected function setUp(): void
    {
        /** @var Application */
        $app = require __DIR__ . '/../setting/bootstrap.php';

        $this->setUpContainer($app);
        $this->setUpHttp();
        $this->setUpConsole();
    }
}
