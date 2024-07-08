<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Contract\ActiveThemeIdMatcher;
use CmsTool\Theme\Contract\ThemeAccessor;
use CmsTool\Theme\Contract\ThemeAssetFinfoFactory;
use CmsTool\Theme\Contract\ThemeCustomizationAccessor;
use CmsTool\Theme\Contract\ThemeCustomizationLoader;
use CmsTool\Theme\Contract\ThemeCustomizationSaver;
use CmsTool\Theme\Contract\ThemeFinder;
use CmsTool\Theme\Contract\ThemeLoader;
use CmsTool\Theme\Contract\ThemeSaver;
use CmsTool\Theme\Hook\ThemeHook;
use CmsTool\Theme\Hook\ThemeHookPresets;
use CmsTool\Theme\Routing\ThemeRoute;
use CmsTool\Theme\Routing\ThemeRoutePresets;
use CmsTool\Theme\Schema\DefaultThemeCustomizationAccessor;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Bootstrap\Support\ConfigBasedDefinitionReplacer;
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
            ThemeCustomizationLoader::class => get(ThemeCustomizationAccessor::class),
            ThemeCustomizationSaver::class => get(ThemeCustomizationAccessor::class),
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
            ThemeLoader::class => get(ThemeAccessor::class),
            ThemeSaver::class => get(ThemeAccessor::class),
            ...ConfigBasedDefinitionReplacer::createDependencyDefinitions(
                dependencies: [
                    ThemeFinder::class => DefaultThemeFinder::class,
                    ThemeAccessor::class => DefaultThemeAccessor::class,
                    ActiveThemeIdMatcher::class => DefaultActiveThemeIdMatcher::class,
                    ThemeAssetFinfoFactory::class => DefaultThemeAssetFinfoFactory::class,
                    ThemeCustomizationAccessor::class => DefaultThemeCustomizationAccessor::class,
                ],
                configKeyPrefix: 'theme',
            )
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
