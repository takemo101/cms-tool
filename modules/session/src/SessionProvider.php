<?php

namespace CmsTool\Session;

use CmsTool\Session\Contract\SessionFactory;
use CmsTool\Session\Csrf\CsrfGuard;
use Psr\Container\ContainerInterface;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Config\ConfigPhpRepository;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\Chubby\Filesystem\PathHelper;
use Takemo101\Chubby\Hook\Hook;

use function DI\get;

class SessionProvider implements Provider
{
    /**
     * @var string Provider name.
     */
    public const ProviderName = 'session';

    /**
     * Execute Bootstrap providing process.
     *
     * @param Definitions $definitions
     * @return void
     */
    public function register(Definitions $definitions): void
    {
        $definitions->add([
            'csrf' => get(CsrfGuard::class),
            SessionFactory::class => function (
                ConfigRepository $config,
                ContainerInterface $container,
                Hook $hook,
            ) {
                /** @var class-string<SessionFactory> */
                $class = $config->get('session.factory', NativePhpSessionFactory::class);

                /** @var SessionFactory */
                $factory = $container->get($class);

                /** @var SessionFactory */
                $factory = $hook->filter(
                    SessionFactory::class,
                    $factory,
                );

                return $factory;
            }
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
            'session',
            ConfigPhpRepository::getConfigByPath(
                $helper->join(
                    dirname(__DIR__, 1),
                    'config',
                    'session.php',
                ),
            ),
        );
    }
}
