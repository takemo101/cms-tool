<?php

namespace CmsTool\Session;

use CmsTool\Session\Contract\SessionFactory;
use CmsTool\Session\Csrf\CsrfGuard;
use CmsTool\Session\Flash\FlashSession;
use CmsTool\Session\Flash\FlashSessionsFactory;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Bootstrap\Support\ConfigBasedDefinitionReplacer;
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
            SessionFactory::class => new ConfigBasedDefinitionReplacer(
                NativePhpSessionFactory::class,
                'session.factory',
            ),
            FlashSessionsFactory::class => function (
                ConfigRepository $config,
                Hook $hook,
            ) {
                /** @var class-string<FlashSession>[] */
                $flashes = $config->get('session.flashes', []);

                $factory = new FlashSessionsFactory(...$flashes);

                $hook->doTyped($factory);

                return $factory;
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
            'session',
            ConfigPhpRepository::getConfigByPath(
                $helper->join(dirname(__DIR__, 1), 'config', 'session.php')
            ),
            false,
        );
    }
}
