<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Contract\ThemeFinder;
use CmsTool\Theme\Contract\ThemeLoader;
use Psr\Container\ContainerInterface;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\Chubby\Filesystem\PathHelper;
use Takemo101\Chubby\Config\ConfigPhpRepository;
use Takemo101\Chubby\Hook\Hook;

use function DI\factory;

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
            ThemeFinder::class => function (
                ContainerInterface $container,
                ConfigRepository $config,
                Hook $hook,
            ) {
                /** @var class-string<ThemeFinder> */
                $class = $config->get(
                    'theme.finder',
                    DefaultThemeFinder::class,
                );

                /** @var ThemeFinder */
                $finder = $container->get($class);

                /** @var ThemeFinder */
                $finder = $hook->filter(
                    ThemeFinder::class,
                    $finder,
                );

                return $finder;
            },
            ThemeLoader::class => function (
                ContainerInterface $container,
                ConfigRepository $config,
                Hook $hook,
            ) {
                /** @var class-string<ThemeLoader> */
                $class = $config->get(
                    'theme.loader',
                    DefaultThemeLoader::class,
                );

                /** @var ThemeLoader */
                $loader = $container->get($class);

                /** @var ThemeLoader */
                $loader = $hook->filter(
                    ThemeLoader::class,
                    $loader,
                );

                return $loader;
            },
            ActiveThemeId::class => function (
                DefaultThemeId $defaultThemeId,
                Hook $hook,
            ) {
                $activeThemeId = ActiveThemeId::fromThemeId($defaultThemeId);

                $hook->doByObject($activeThemeId);

                return $activeThemeId;
            },
            ActiveTheme::class => factory([ActiveThemeFactory::class, 'create']),
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
