<?php

namespace Takemo101\CmsTool\Http\Routing;

use Takemo101\CmsTool\Http\Action\Theme\FixedPageAction;
use Takemo101\CmsTool\Http\Action\Theme\HomeAction;
use Takemo101\CmsTool\Support\Theme\ActiveThemeRouteRegister;
use Slim\Interfaces\RouteCollectorProxyInterface as Proxy;
use Slim\Interfaces\RouteGroupInterface;

class ThemeRouteGroupHandler
{
    /**
     * Base path for the route group
     */
    public const BasePath = '';

    /**
     * constructor
     *
     * @param ActiveThemeRouteRegister $register
     */
    public function __construct(
        private readonly ActiveThemeRouteRegister $register
    ) {
        //
    }

    /**
     * Handler to set up routing for themes.
     *
     * @param Proxy $proxy
     * @param ActiveThemeRouteRegister $register
     * @return void
     */
    public function __invoke(Proxy $proxy): void
    {
        $proxy->get(
            '/',
            HomeAction::class,
        )->setName('home');

        // Set routing for theme
        $this->register->register($proxy);

        $proxy->get(
            '/{path:.+}',
            FixedPageAction::class,
        )->setName('fixed-page');
    }

    /**
     * Configure routing for themes.
     *
     * @param Proxy $proxy
     * @return RouteGroupInterface
     */
    public static function configure(Proxy $proxy): RouteGroupInterface
    {
        return $proxy->group(
            self::BasePath,
            self::class,
        );
    }
}
