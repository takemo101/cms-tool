<?php

namespace Takemo101\CmsTool;

use CmsTool\Session\SessionProvider;
use CmsTool\Support\SupportProvider;
use CmsTool\Theme\ThemeProvider;
use CmsTool\View\ViewProvider;
use Takemo101\Chubby\Application;
use Takemo101\Chubby\ApplicationBuilder;
use Takemo101\Chubby\ApplicationOption;
use Takemo101\Chubby\Bootstrap\Provider\DependencyProvider;
use Takemo101\Chubby\Bootstrap\Provider\FunctionProvider;

class CmsToolApplicationBuilder
{
    /**
     * @param ApplicationOption|null $option
     * @return Application
     */
    public static function build(
        ?ApplicationOption $option = null,
    ): Application {
        $app = ApplicationBuilder::buildStandard($option);

        $app->getProvider(DependencyProvider::class)
            ->setDependencyPath(__DIR__ . '/dependency.php');

        $app->getProvider(FunctionProvider::class)
            ->setFunctionPath(__DIR__ . '/function.php');

        return $app->addProvider(
            ViewProvider::class,
            SessionProvider::class,
            SupportProvider::class,
            ThemeProvider::class,
            CmsToolProvider::class,
        );
    }
}
