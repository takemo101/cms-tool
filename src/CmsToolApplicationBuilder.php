<?php

namespace Takemo101\CmsTool;

use CmsTool\Support\SupportProvider;
use CmsTool\View\ViewProvider;
use Takemo101\Chubby\Application;
use Takemo101\Chubby\ApplicationBuilder;
use Takemo101\Chubby\ApplicationOption;
use Takemo101\CmsTool\Provider\CmsToolProvider;

final class CmsToolApplicationBuilder
{
    /**
     * @param ApplicationOption|null $option
     * @return Application
     */
    public static function build(
        ?ApplicationOption $option = null,
    ): Application {
        $app = ApplicationBuilder::buildStandard($option);

        return $app->addProvider(
            new CmsToolProvider(),
            new ViewProvider(),
            new SupportProvider(),
        );
    }
}
