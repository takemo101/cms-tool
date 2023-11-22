<?php

namespace Takemo101\CmsTool;

use CmsTool\Session\Csrf\CsrfGuardContext;
use CmsTool\Session\Flash\FlashSessionsContext;
use CmsTool\Session\SessionContext;
use CmsTool\View\Html\Filter\FormAppendFilters;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Hook\Hook;
use Takemo101\Chubby\Support\ApplicationPath;
use Takemo101\CmsTool\Support\FormAppendFilter\AppendCsrfInputFilter;
use Takemo101\CmsTool\Support\Session\FlashErrorMessages;
use Takemo101\CmsTool\Support\Session\FlashOldInputs;
use Takemo101\CmsTool\Support\Twig\ErrorExtension;
use Takemo101\CmsTool\Support\Twig\FlashExtension;
use Takemo101\CmsTool\Support\Twig\OldExtension;
use Takemo101\CmsTool\Support\Twig\SessionExtension;
use Takemo101\CmsTool\Support\VendorPath;
use Twig\Environment;

final class CmsToolProvider implements Provider
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
        ]);

        $definitions->add(
            require __DIR__ . DIRECTORY_SEPARATOR . 'dependency.php',
        );
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

        $hook = $container->get(Hook::class);

        $this->bootHtml($hook);
        $this->bootTwig($hook);
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

                    $guard = CsrfGuardContext::fromServerRequest(
                        $request,
                    )->getGuard();

                    $filter->setCsrfToken(
                        $guard->getToken(),
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

                    $flashSessions = FlashSessionsContext::fromServerRequest($request)
                        ->getFlashSessions();

                    $twig->getExtension(ErrorExtension::class)
                        ->setErrors(
                            $flashSessions->get(FlashErrorMessages::class),
                        );

                    $twig->getExtension(OldExtension::class)
                        ->setOldInputs(
                            $flashSessions->get(FlashOldInputs::class),
                        );

                    $session = SessionContext::fromServerRequest($request)
                        ->getSession();

                    $twig->getExtension(SessionExtension::class)
                        ->setSession($session);

                    $twig->getExtension(FlashExtension::class)
                        ->setFlash($session->getFlash());


                    return $request;
                }
            );
    }
}
