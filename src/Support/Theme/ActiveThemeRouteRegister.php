<?php

namespace Takemo101\CmsTool\Support\Theme;

use CmsTool\Theme\ActiveThemeFactory;
use CmsTool\Theme\Routing\NotFoundThemePresetException;
use CmsTool\Theme\Routing\ThemeRoutePresetResolver;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Takemo101\Chubby\Hook\Hook;
use Takemo101\CmsTool\Domain\Install\InstallRepository;

use const Takemo101\CmsTool\HookTags\RegisterThemeRoute;

class ActiveThemeRouteRegister
{
    /**
     * constructor
     *
     * @param InstallRepository $repository
     * @param ActiveThemeFactory $factory
     * @param ThemeRoutePresets $presets
     * @param Hook $hook
     */
    public function __construct(
        private InstallRepository $repository,
        private ActiveThemeFactory $factory,
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
        if (!$this->repository->isInstalled()) {
            return;
        }

        $activeTheme = $this->factory->create();

        // If there is a preset setting, register the preset route
        if ($name = $activeTheme->setting->preset) {
            $route = $this->resolver->resolve($name);

            $route->route($proxy);
        }

        // Register the route of the theme
        $this->hook->do(RegisterThemeRoute, $proxy);
    }
}
