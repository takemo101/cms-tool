<?php

namespace Takemo101\CmsTool\Http\Support;

use Slim\App as Slim;
use Slim\Middleware\MethodOverrideMiddleware;
use Takemo101\Chubby\Http\Configurer\DefaultSlimConfigurer;

class CmsToolConfigurer extends DefaultSlimConfigurer
{
    /**
     * Configure Slim application settings.
     *
     * @param Slim $slim
     * @return Slim
     */
    public function configure(Slim $slim): Slim
    {
        $slim = parent::configure($slim);

        $slim->add(new MethodOverrideMiddleware());

        return $slim;
    }
}
