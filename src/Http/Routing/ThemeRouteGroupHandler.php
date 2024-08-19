<?php

namespace Takemo101\CmsTool\Http\Routing;

use Takemo101\CmsTool\Http\Action\Theme\FixedPageAction;
use Takemo101\CmsTool\Http\Action\Theme\HomeAction;
use Takemo101\CmsTool\Support\Theme\ActiveThemeRouteRegister;
use Slim\Interfaces\RouteCollectorProxyInterface as Proxy;
use Slim\Interfaces\RouteGroupInterface;
use Takemo101\CmsTool\Preset\Shared\Feed\FeedActionAndResponseRenderer;

class ThemeRouteGroupHandler
{
    /**
     * Base pattern for the route group
     */
    public const BasePattern = '';

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
        $this->registerBeforeBaseRoutes($proxy);

        // Set routing for theme
        $this->register->register($proxy);

        $this->registerAfterBaseRoutes($proxy);
    }

    /**
     * Register before base routes.
     *
     * @param Proxy $proxy
     * @return void
     */
    private function registerBeforeBaseRoutes(Proxy $proxy): void
    {
        $proxy->get(
            empty($proxy->getBasePath()) ? '/' : '',
            HomeAction::class,
        )->setName('home');

        $proxy->get(
            '/feed',
            FeedActionAndResponseRenderer::class,
        )->setName('feed');
    }

    /**
     * Register after base routes.
     *
     * @param Proxy $proxy
     * @return void
     */
    private function registerAfterBaseRoutes(Proxy $proxy): void
    {
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
            self::BasePattern,
            self::class,
        );
    }
}
