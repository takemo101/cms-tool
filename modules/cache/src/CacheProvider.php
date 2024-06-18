<?php

namespace CmsTool\Cache;

use CmsTool\Cache\Command\CacheCleanCommand;
use CmsTool\Cache\Contract\CacheItemPoolFactory;
use Psr\Cache\CacheItemPoolInterface;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Bootstrap\Support\ConfigBasedDefinitionReplacer;
use Takemo101\Chubby\Config\ConfigPhpRepository;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\Chubby\Console\CommandCollection;
use Takemo101\Chubby\Filesystem\PathHelper;
use Takemo101\Chubby\Hook\Hook;

class CacheProvider implements Provider
{
    /**
     * @var string Provider name.
     */
    public const ProviderName = 'cache';

    /**
     * Execute Bootstrap providing process.
     *
     * @param Definitions $definitions
     * @return void
     */
    public function register(Definitions $definitions): void
    {
        $definitions->add([
            CacheItemPoolFactory::class => new ConfigBasedDefinitionReplacer(
                FilesystemCacheItemPoolFactory::class,
                'cache.factory',
            ),
            CacheItemPoolInterface::class => fn (CacheItemPoolFactory $factory) => $factory->create(),
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
            'cache',
            ConfigPhpRepository::getConfigByPath(
                $helper->join(dirname(__DIR__, 1), 'config', 'cache.php')
            ),
            false,
        );

        require $helper->join(__DIR__, 'helper.php');

        /** @var Hook */
        $hook = $container->get(Hook::class);

        $hook->onTyped(
            fn (CommandCollection $commands) => $commands->add(
                CacheCleanCommand::class,
            ),
        );
    }
}
