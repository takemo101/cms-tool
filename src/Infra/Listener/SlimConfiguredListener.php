<?php

namespace Takemo101\CmsTool\Infra\Listener;

use Slim\Middleware\MethodOverrideMiddleware;
use Takemo101\Chubby\Event\Attribute\AsEventListener;
use Takemo101\Chubby\Http\Event\AfterSlimConfiguration;

#[AsEventListener(AfterSlimConfiguration::class)]
class SlimConfiguredListener
{
    /**
     * @param AfterSlimConfiguration $event
     * @return void
     */
    public function __invoke(
        AfterSlimConfiguration $event
    ): void {
        $slim = $event->getSlim();

        $slim->add(new MethodOverrideMiddleware());
    }
}
