<?php

namespace Takemo101\CmsTool\Support\Theme;

use CmsTool\Theme\Routing\NotFoundThemePresetException;
use CmsTool\Theme\Routing\ThemeRoutePresetResolver;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Takemo101\Chubby\Hook\Hook;
use Takemo101\CmsTool\Domain\Theme\ActiveThemeRepository;

use const Takemo101\CmsTool\HookTags\RegisterThemeRoute;

class ActiveThemeRouteRegister
{
    /**
     * constructor
     *
     * @param ActiveThemeRepository $repository
     * @param ThemeRoutePresetResolver $resolver
     * @param Hook $hook
     */
    public function __construct(
        private ActiveThemeRepository $repository,
        private ThemeRoutePresetResolver $resolver,
        private Hook $hook,
    ) {
        //
    }

    /**
     * Register theme route
     *
     * @param RouteCollectorProxyInterface $proxy
     * @return void
     * @throws NotFoundThemePresetException
     */
    public function register(RouteCollectorProxyInterface $proxy): void
    {
        $activeTheme = $this->repository->find();

        if (!$activeTheme) {
            return;
        }

        // If there is a preset setting, register the preset route
        if ($name = $activeTheme->setting->preset) {
            $route = $this->resolver->resolve($name);

            $route?->route($activeTheme, $proxy);
        }

        // Register the route of the theme
        $this->hook->do(RegisterThemeRoute, $proxy);
    }
}
