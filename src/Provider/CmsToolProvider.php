<?php

namespace Takemo101\CmsTool\Provider;

use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\CmsTool\Support\VendorPath;

final class CmsToolProvider implements Provider
{
    /**
     * @var string Provider name.
     */
    public const ProviderName = 'cms-tool';

    /**
     * Execute Bootstrap providing process.
     *
     * @param Definitions $definitions
     * @return void
     */
    public function register(Definitions $definitions): void
    {
        $definitions->add([
            VendorPath::class => fn () => new VendorPath(
                dirname(__DIR__, 2),
                'src',
                'config' . DIRECTORY_SEPARATOR . 'vendor',
                'resources',
            ),
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
        /** @var VendorPath */
        $path = $container->get(VendorPath::class);

        /** @var ConfigRepository */
        $config = $container->get(ConfigRepository::class);

        // Load default config files.
        $config->load(
            $path->getConfigPath(),
        );

        // Load helper functions.
        require $path->getSourcePath('helper.php');

        // Load functions.php
        require $path->getSourcePath('function.php');
    }
}
