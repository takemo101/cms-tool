<?php

namespace Takemo101\CmsTool;

use CmsTool\Session\Csrf\CsrfGuardContext;
use CmsTool\Session\Flash\FlashSessionsContext;
use CmsTool\Session\SessionContext;
use CmsTool\View\Accessor\DataAccessors;
use CmsTool\View\Html\Filter\FormAppendFilters;
use CmsTool\View\ViewCreator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Hook\Hook;
use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Http\Session\AdminSessionContext;
use Takemo101\CmsTool\Http\Session\AdminSessionFactory;
use Takemo101\CmsTool\Http\Session\DefaultAdminSessionFactory;
use Takemo101\CmsTool\Support\Accessor\ServerRequestAccessor;
use Takemo101\CmsTool\Support\FormAppendFilter\AppendCsrfInputFilter;
use Takemo101\CmsTool\Support\Session\FlashErrorMessages;
use Takemo101\CmsTool\Support\Session\FlashOldInputs;
use Takemo101\CmsTool\Support\Theme\ActiveThemeFunctionLoader;
use Takemo101\CmsTool\Support\Twig\ErrorExtension;
use Takemo101\CmsTool\Support\Twig\FlashExtension;
use Takemo101\CmsTool\Support\Twig\OldExtension;
use Takemo101\CmsTool\Support\Twig\SessionExtension;
use Takemo101\CmsTool\Support\VendorPath;
use Takemo101\CmsTool\Support\Webhook\WebhookHandler;
use Takemo101\CmsTool\Support\Webhook\WebhookHandlers;
use Twig\Environment;

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

                $hook->doByType($handlers);

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
        $this->bootTwig($hook);

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
            ->onByType(
                function (
                    ServerRequestInterface $request,
                    ContainerInterface $container,
                ) {
                    /** @var AppendCsrfInputFilter */
                    $filter = $container->get(AppendCsrfInputFilter::class);

                    /** @var ViewCreator */
                    $view = $container->get(ViewCreator::class);

                    /** @var DataAccessors */
                    $dataAccessors = $container->get(DataAccessors::class);

                    // CsrfGuardContext generation processing is set in consideration of when the CSRF middleware is not executed.
                    if ($token = CsrfGuardContext::fromRequest(
                        $request,
                        fn () => $container->get(CsrfGuardContext::class),
                    )
                        ?->getGuard()
                        ->getToken()
                    ) {
                        $filter->setCsrfToken($token);

                        $view->share('csrf', $token);
                    }

                    // If there is an administrator session, pass the administrator information to the view
                    if ($adminSession = AdminSessionContext::fromRequest($request)
                        ?->getAdminSession()
                    ) {
                        /** @var RootAdminRepository */
                        $repository = $container->get(RootAdminRepository::class);

                        $view
                            ->share(
                                'auth',
                                $adminSession->isLoggedIn()
                                    ? $repository->find($adminSession->getId())
                                    : null,
                            );
                    }

                    // Put old inputs to flash session.
                    /** @var array<string,mixed> */
                    $params = [
                        ...$request->getQueryParams(),
                        ...(array) $request->getParsedBody(),
                    ];

                    if (!empty($params)) {
                        FlashSessionsContext::fromRequest($request)
                            ?->getFlashSessions()
                            ->get(FlashOldInputs::class)
                            ->put($params);
                    }

                    $dataAccessors->add(
                        'request',
                        ServerRequestAccessor::class,
                        [
                            'request' => $request,
                        ]
                    );

                    return $request;
                }
            )
            ->onByType(
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
     * Boot twig.
     *
     * @param Hook $hook
     * @return void
     */
    private function bootTwig(Hook $hook): void
    {
        $hook
            ->onByType(
                function (ServerRequestInterface $request, ContainerInterface $container) {
                    /** @var Environment */
                    $twig = $container->get(Environment::class);

                    if ($flashSessions = FlashSessionsContext::fromRequest($request)
                        ?->getFlashSessions()
                    ) {
                        $twig->getExtension(ErrorExtension::class)
                            ->setErrors(
                                $flashSessions->get(FlashErrorMessages::class),
                            );

                        $twig->getExtension(OldExtension::class)
                            ->setOldInputs(
                                $flashSessions->get(FlashOldInputs::class),
                            );
                    }

                    if ($session = SessionContext::fromRequest($request)
                        ?->getSession()
                    ) {
                        $twig->getExtension(SessionExtension::class)
                            ->setSession($session);

                        $twig->getExtension(FlashExtension::class)
                            ->setFlash($session->getFlash());
                    }

                    return $request;
                }
            );
    }
}
