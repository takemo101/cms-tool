<?php

namespace Tests\Session;

use CmsTool\Session\SessionProvider;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Takemo101\Chubby\Application;
use Takemo101\Chubby\ApplicationBuilder;
use Takemo101\Chubby\ApplicationOption;
use Takemo101\Chubby\Test\HasContainerTest;
use Takemo101\Chubby\Test\HasHttpTest;

class TestCase extends BaseTestCase
{
    use HasContainerTest;
    use HasHttpTest;

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
            ->getApplication()
            ->addProvider(new SessionProvider());

        $this->setUpContainer($app);
        $this->setUpHttp();
    }
}
