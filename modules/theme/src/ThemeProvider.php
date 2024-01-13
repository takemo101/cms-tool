<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Contract\ActiveThemeIdMatcher;
use CmsTool\Theme\Contract\ThemeAccessor;
use CmsTool\Theme\Contract\ThemeAssetFinfoFactory;
use CmsTool\Theme\Contract\ThemeFinder;
use CmsTool\Theme\Contract\ThemeLoader;
use CmsTool\Theme\Contract\ThemeSaver;
use CmsTool\Theme\Hook\ThemeHook;
use CmsTool\Theme\Hook\ThemeHookPresets;
use CmsTool\Theme\Routing\ThemeRoute;
use CmsTool\Theme\Routing\ThemeRoutePresets;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Bootstrap\DefinitionHelper;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\Chubby\Filesystem\PathHelper;
use Takemo101\Chubby\Config\ConfigPhpRepository;
use Takemo101\Chubby\Hook\Hook;

use function DI\get;

class ThemeProvider implements Provider
{
    /**
     * @var string Provider name.
     */
    public const ProviderName = 'theme';

    /**
     * Execute Bootstrap providing process.
     *
     * @param Definitions $definitions
     * @return void
     */
    public function register(Definitions $definitions): void
    {
        $definitions->add([
            ThemeFinder::class => DefinitionHelper::createReplaceable(
                ThemeFinder::class,
                'theme.finder',
                DefaultThemeFinder::class,
                true,
            ),
            ThemeAccessor::class => DefinitionHelper::createReplaceable(
                ThemeAccessor::class,
                'theme.accessor',
                DefaultThemeAccessor::class,
                true,
            ),
            ThemeLoader::class => get(ThemeAccessor::class),
            ThemeSaver::class => get(ThemeAccessor::class),
            ActiveThemeIdMatcher::class => DefinitionHelper::createReplaceable(
                ActiveThemeIdMatcher::class,
                'theme.matcher',
                DefaultActiveThemeIdMatcher::class,
            ),
            ThemeAssetFinfoFactory::class => DefinitionHelper::createReplaceable(
                ThemeAssetFinfoFactory::class,
                'theme.factory',
                DefaultThemeAssetFinfoFactory::class,
            ),
            ThemeRoutePresets::class => function (
                ConfigRepository $config,
                Hook $hook,
            ) {
                /** @var array<string,class-string<ThemeRoute>> */
                $routes = $config->get('theme.routes', []);

                $presets = new ThemeRoutePresets($routes);

                $hook->doTyped($presets);

                return $presets;
            },
            ThemeHookPresets::class => function (
                ConfigRepository $config,
                Hook $hook,
            ) {
                /** @var array<string,class-string<ThemeHook>> */
                $hooks = $config->get('theme.hooks', []);

                $presets = new ThemeHookPresets($hooks);

                $hook->doTyped($presets);

                return $presets;
            },
        ]);
    }

    /**
     * Execute Bootstrap booting process.
     *
     * @param ApplicationContainer $container
     * @return void
     */
    public function boot(ApplicationContainer $container): void
    {
        /** @var PathHelper */
        $helper = $container->get(PathHelper::class);

        /** @var ConfigRepository */
        $config = $container->get(ConfigRepository::class);

        $config->merge(
            'theme',
            ConfigPhpRepository::getConfigByPath(
                $helper->join(dirname(__DIR__, 1), 'config', 'theme.php')
            ),
            false,
        );
    }
}
