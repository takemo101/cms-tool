<?php

namespace CmsTool\Cache;

use CmsTool\Cache\Command\CacheCleanCommand;
use CmsTool\Cache\Contract\CacheAdapterFactory;
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface as Psr16CacheInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Marshaller\DefaultMarshaller;
use Symfony\Component\Cache\Marshaller\MarshallerInterface;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Cache\ResettableInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Bootstrap\DefinitionHelper;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Config\ConfigPhpRepository;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\Chubby\Console\CommandCollection;
use Takemo101\Chubby\Filesystem\PathHelper;
use Takemo101\Chubby\Hook\Hook;

use function DI\get;
use function DI\factory;

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
            MarshallerInterface::class => DefinitionHelper::createReplaceable(
                MarshallerInterface::class,
                'cache.marshaller',
                DefaultMarshaller::class,
            ),
            CacheAdapterFactory::class => DefinitionHelper::createReplaceable(
                CacheAdapterFactory::class,
                'cache.factory',
                FilesystemAdapterFactory::class,
            ),
            CacheInterface::class => factory([CacheAdapterFactory::class, 'create']),
            AdapterInterface::class => get(CacheInterface::class),
            ResettableInterface::class => get(CacheInterface::class),
            CacheItemPoolInterface::class => get(CacheInterface::class),
            Psr16CacheInterface::class => fn (
                CacheItemPoolInterface $pool,
            ) => new Psr16Cache($pool),
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
