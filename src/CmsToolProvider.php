<?php

namespace Takemo101\CmsTool;

use CmsTool\Support\Translation\TranslationAccessor;
use CmsTool\Support\Translation\TranslationLoader;
use CmsTool\View\Html\Filter\FormAppendFilters;
use Psr\Container\ContainerInterface;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Hook\Hook;
use Takemo101\CmsTool\Http\Session\AdminSessionFactory;
use Takemo101\CmsTool\Http\Session\DefaultAdminSessionFactory;
use Takemo101\CmsTool\Support\FormAppendFilter\AppendCsrfInputFilter;
use Takemo101\CmsTool\Support\Theme\ActiveThemeFunctionLoader;
use Takemo101\CmsTool\Support\VendorPath;
use Takemo101\CmsTool\Support\Webhook\WebhookHandler;
use Takemo101\CmsTool\Support\Webhook\WebhookHandlers;

class CmsToolProvider implements Provider
{
    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     */
    public function __construct(
        private LocalFilesystem $filesystem,
    ) {
        //
    }

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
                dirname(__DIR__, 1),
                'src',
                'config' . DIRECTORY_SEPARATOR . 'vendor',
                'resources',
            ),
            AdminSessionFactory::class => function (
                ContainerInterface $container,
                ConfigRepository $config,
            ) {
                /** @var class-string<AdminSessionFactory> */
                $class = $config->get(
                    'auth.admin.session.factory',
                    DefaultAdminSessionFactory::class,
                );

                return $container->get($class);
            },
            WebhookHandlers::class => function (
                ConfigRepository $config,
                Hook $hook,
            ) {
                /** @var class-string<WebhookHandler>[] */
                $classes = $config->get(
                    'system.webhook.handlers',
                    [],
                );

                $handlers = new WebhookHandlers(...$classes);

                $hook->doTyped($handlers);

                return $handlers;
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
        /** @var VendorPath */
        $path = $container->get(VendorPath::class);

        /** @var ConfigRepository */
        $config = $container->get(ConfigRepository::class);

        // Load default config files.
        $config->load(
            $path->getConfigPath(),
            true,
        );

        // Load helper functions.
        $this->filesystem->require($path->getSourcePath('helper.php'));
        // Load hook tags.
        $this->filesystem->require($path->getSourcePath('tags.php'));

        $hook = $container->get(Hook::class);

        $this->bootHtml($hook);
        $this->bootTranslation($hook);

        /** @var ActiveThemeFunctionLoader */
        $functionLoader = $container->get(ActiveThemeFunctionLoader::class);

        $functionLoader->load();
    }

    /**
     * Boot html.
     *
     * @param Hook $hook
     * @return void
     */
    private function bootHtml(Hook $hook): void
    {
        $hook
            ->onTyped(
                function (
                    FormAppendFilters $filters,
                    ContainerInterface $container,
                ) {
                    /** @var AppendCsrfInputFilter */
                    $csrf = $container->get(AppendCsrfInputFilter::class);

                    $filters->addFilter($csrf);
                }
            );
    }

    /**
     * Boot translation.
     *
     * @param Hook $hook
     * @return void
     */
    private function bootTranslation(Hook $hook): void
    {
        $hook
            ->onTyped(
                function (
                    TranslationAccessor $accessor,
                    ContainerInterface $container,
                ) {
                    /** @var VendorPath */
                    $path = $container->get(VendorPath::class);

                    $accessor->addResource(
                        $path->getResourcePath('lang'),
                    );
                }
            );
    }
}
