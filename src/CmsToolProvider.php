<?php

namespace Takemo101\CmsTool;

use CmsTool\Session\Middleware\SessionStart;
use CmsTool\Support\Feed\FeedGenerator;
use CmsTool\Support\Translation\TranslationAccessor;
use CmsTool\Theme\Contract\ThemeFinder;
use CmsTool\View\Contract\TemplateFinder;
use CmsTool\View\Html\Filter\FormAppendFilters;
use Psr\Container\ContainerInterface;
use Slim\App as Slim;
use Slim\Middleware\MethodOverrideMiddleware;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\ApplicationHookTags;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Hook\Hook;
use Takemo101\Chubby\Http\GlobalMiddlewareCollection;
use Takemo101\CmsTool\Support\BasicAuth\BasicAuth;
use Takemo101\CmsTool\Support\BasicAuth\BasicAuthFactory;
use Takemo101\CmsTool\Http\Middleware\CacheControl;
use Takemo101\CmsTool\Http\Session\AdminSessionFactory;
use Takemo101\CmsTool\Http\Session\DefaultAdminSessionFactory;
use Takemo101\CmsTool\Preset\Shared\Feed\FeedLinkHtml;
use Takemo101\CmsTool\Preset\Shared\Feed\FeedActionAndResponseRenderer;
use Takemo101\CmsTool\Support\FormAppendFilter\AppendCsrfInputFilter;
use Takemo101\CmsTool\Support\Theme\ActiveThemeFunctionLoader;
use Takemo101\CmsTool\Support\VendorPath;
use Takemo101\CmsTool\Support\Webhook\CacheCleanWebhookHandler;
use Takemo101\CmsTool\Support\Webhook\WebhookHandler;
use Takemo101\CmsTool\Support\Webhook\WebhookHandlers;
use Takemo101\CmsTool\Support\BasicAuth\BasicAuthUsers;
use Takemo101\CmsTool\Support\Htmlable\HeadHtmls;

class CmsToolProvider implements Provider
{
    /**
     * @var string CmsTool Version number.
     */
    public const Version = '0.1.6';

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
                'config',
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

                $handlers = new WebhookHandlers(...[
                    ...$classes,
                    CacheCleanWebhookHandler::class,
                ]);

                $hook->doTyped($handlers);

                return $handlers;
            },
            BasicAuth::class => function (
                BasicAuthFactory $factory,
                ConfigRepository $config,
            ) {
                /** @var boolean */
                $enabled = $config->get('system.basic_auth.enabled', false);
                /** @var string */
                $realm = $config->get('system.basic_auth.realm', 'Web');
                /** @var array<string,string> */
                $users = $config->get('system.basic_auth.users', []);

                return $factory->create(
                    users: new BasicAuthUsers($users),
                    realm: $realm,
                    enabled: $enabled,
                );
            },
            HeadHtmls::class => fn (
                Hook $hook,
                FeedLinkHtml $feedLink,
            ) => $hook->doTyped(
                new HeadHtmls($feedLink),
            ),
            FeedActionAndResponseRenderer::class => fn (
                Hook $hook,
                FeedGenerator $generator,
                ApplicationContainer $container,
            ) => $hook->doTyped(
                new FeedActionAndResponseRenderer(
                    generator: $generator,
                    container: $container,
                ),
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
            true,
        );
        $config->load(
            $path->getConfigPath('vendor'),
            true,
        );

        // Load helper functions.
        $this->filesystem->require($path->getSourcePath('helper.php'));

        /** @var Hook */
        $hook = $container->get(Hook::class);

        $this->bootTemplate($hook, $path);
        $this->bootTheme($hook, $path);
        $this->bootHtml($hook);
        $this->bootTranslation($hook);
        $this->bootGlobalMiddleware($hook);

        /** @var ActiveThemeFunctionLoader */
        $functionLoader = $container->get(ActiveThemeFunctionLoader::class);

        $functionLoader->load();
    }

    /**
     * Boot view.
     *
     * @param Hook $hook
     * @param VendorPath $path
     * @return void
     */
    private function bootTemplate(Hook $hook, VendorPath $path): void
    {
        $hook
            ->onTyped(
                fn (TemplateFinder $finder) => $finder->addNamespace(
                    'cms-tool',
                    $path->getResourcePath('views'),
                )
            );
    }

    /**
     * Boot theme.
     *
     * @param Hook $hook
     * @param VendorPath $path
     * @return void
     */
    private function bootTheme(Hook $hook, VendorPath $path): void
    {
        $hook
            ->onTyped(
                fn (ThemeFinder $finder) => $finder->addLocation(
                    $path->getResourcePath('themes'),
                )
            );
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

    /**
     * Boot global middleware.
     *
     * @param Hook $hook
     * @return void
     */
    private function bootGlobalMiddleware(Hook $hook): void
    {
        $hook
            ->onTyped(
                fn (GlobalMiddlewareCollection $middlewares) => $middlewares->add(
                    CacheControl::class,
                    SessionStart::class,
                ),
            )
            ->on(
                ApplicationHookTags::Http_AfterAddRoutingMiddleware,
                fn (Slim $slim) => $slim->add(
                    new MethodOverrideMiddleware(),
                ),
            );
    }
}
