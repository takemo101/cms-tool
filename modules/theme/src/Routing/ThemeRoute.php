<?php

namespace CmsTool\Theme\Routing;

use CmsTool\Theme\Theme;
use Slim\Interfaces\RouteCollectorProxyInterface;

interface ThemeRoute
{
    /**
     * Register theme route
     *
     * @param Theme $theme
     * @param RouteCollectorProxyInterface $proxy
     * @return void
     */
    public function route(
        Theme $theme,
        RouteCollectorProxyInterface $proxy,
    ): void;
}
