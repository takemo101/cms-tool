<?php

namespace CmsTool\Theme\Routing;

use Slim\Interfaces\RouteCollectorProxyInterface;

interface ThemeRoute
{
    /**
     * Register theme route
     *
     * @param RouteCollectorProxyInterface $proxy
     * @return void
     */
    public function route(RouteCollectorProxyInterface $proxy): void;
}
