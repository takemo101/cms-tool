<?php

// Executed after DI container dependency settings are completed.
// Here, mainly configure routing and middleware.

use CmsTool\Session\Middleware\Csrf;
use Psr\Container\ContainerInterface;
use Slim\Interfaces\RouteCollectorProxyInterface as Proxy;
use Takemo101\Chubby\Console\CommandCollection;
use Takemo101\Chubby\Event\EventRegister;
use Takemo101\Chubby\Http\ErrorHandler\ErrorResponseRenders;
use Takemo101\Chubby\Http\SlimHttp;
use Takemo101\Chubby\Support\ApplicationSummary;
use Takemo101\CmsTool\Console\GenerateBasicAuthPasswordCommand;
use Takemo101\CmsTool\Console\StorageLinkCommand;
use Takemo101\CmsTool\Error\SystemErrorPageRender;
use Takemo101\CmsTool\Error\ThemeErrorPageRender;
use Takemo101\CmsTool\Http\Action\VendorAssetAction;
use Takemo101\CmsTool\Http\Controller\InstallController;
use Takemo101\CmsTool\Error\ValidationErrorResponseRender;
use Takemo101\CmsTool\Http\Action\PhpInfoAction;
use Takemo101\CmsTool\Http\Action\SitePublishAction;
use Takemo101\CmsTool\Http\Action\StorageAssetAction;
use Takemo101\CmsTool\Http\Action\Theme\ActiveThemeAssetAction;
use Takemo101\CmsTool\Http\Action\WebhookAction;
use Takemo101\CmsTool\Http\Action\ThemeAssetAction;
use Takemo101\CmsTool\Http\Action\ThemePreviewAction;
use Takemo101\CmsTool\Http\Controller\Admin\AdminAccountController;
use Takemo101\CmsTool\Http\Controller\Admin\BasicSettingController;
use Takemo101\CmsTool\Http\Controller\Admin\CacheController;
use Takemo101\CmsTool\Http\Controller\Admin\DashboardController;
use Takemo101\CmsTool\Http\Controller\Admin\LoginController;
use Takemo101\CmsTool\Http\Controller\Admin\MicroCmsApiController;
use Takemo101\CmsTool\Http\Controller\Admin\RobotsTxtController;
use Takemo101\CmsTool\Http\Controller\Admin\SiteMetaController;
use Takemo101\CmsTool\Http\Controller\Admin\SiteSeoController;
use Takemo101\CmsTool\Http\Controller\Admin\ThemeController;
use Takemo101\CmsTool\Http\Controller\Admin\ThemeCustomizationController;
use Takemo101\CmsTool\Http\Controller\Admin\ThemeMetaController;
use Takemo101\CmsTool\Http\Controller\Admin\TrackingCodeController;
use Takemo101\CmsTool\Http\Controller\Admin\UninstallController;
use Takemo101\CmsTool\Http\Controller\Admin\WebhookController;
use Takemo101\CmsTool\Http\Middleware\AdminAuth;
use Takemo101\CmsTool\Http\Middleware\AdminSessionStart;
use Takemo101\CmsTool\Support\BasicAuth\BasicAuth;
use Takemo101\CmsTool\Http\Middleware\GuideToInstallation;
use Takemo101\CmsTool\Http\Middleware\InsertTrackingCode;
use Takemo101\CmsTool\Http\Middleware\VerifyActiveThemeCustomizability;
use Takemo101\CmsTool\Http\Middleware\WhenUninstalled;
use Takemo101\CmsTool\Http\Middleware\WhenUnpublished;
use Takemo101\CmsTool\Http\Routing\ThemeRouteGroupHandler;
use Takemo101\CmsTool\Infra\Listener\AdminSessionSetupListener;
use Takemo101\CmsTool\Infra\Listener\ClearCacheListener;
use Takemo101\CmsTool\Infra\Listener\CreateRobotsTxtListener;
use Takemo101\CmsTool\Infra\Listener\CsrfGuardSetupListener;
use Takemo101\CmsTool\Infra\Listener\DeleteRobotsTxtListener;
use Takemo101\CmsTool\Infra\Listener\RequestParameterSetupListener;
use Takemo101\CmsTool\Infra\Storage\LocalPublicStoragePath;
use Takemo101\CmsTool\Support\Htmx\HtmxAccess;

hook()
    ->onTyped(
        fn (CommandCollection $commands) => $commands->add(
            StorageLinkCommand::class,
            GenerateBasicAuthPasswordCommand::class,
        ),
    )
    ->onTyped(
        fn (EventRegister $register) => $register
            ->on(AdminSessionSetupListener::class)
            ->on(CsrfGuardSetupListener::class)
            ->on(RequestParameterSetupListener::class)
            ->on(CreateRobotsTxtListener::class)
            ->on(DeleteRobotsTxtListener::class)
            ->on(ClearCacheListener::class)
    )
    ->onTyped(
        function (
            ErrorResponseRenders $renders,
            ContainerInterface $container,
        ) {
            /** @var ApplicationSummary */
            $summary = $container->get(ApplicationSummary::class);

            if (!$summary->isDebugMode()) {

                /** @var SystemErrorPageRender */
                $systemErrorPageRender = $container->get(SystemErrorPageRender::class);

                /** @var ThemeErrorPageRender */
                $themeErrorPageRender = $container->get(ThemeErrorPageRender::class);

                $renders->addRender(
                    $systemErrorPageRender,
                    $themeErrorPageRender,
                );
            }

            $renders->addRender(
                new ValidationErrorResponseRender(),
            );
        },
    )
    ->onTyped(
        function (
            SlimHttp $http,
            ContainerInterface $container,
        ) {
            /** @var ApplicationSummary */
            $summary = $container->get(ApplicationSummary::class);

            /** @var string */
            $webhookRoutePath = config('system.webhook.route', '/webhook');

            $http->post(
                $webhookRoutePath,
                WebhookAction::class,
            )->setName('webhook');

            if ($summary->isBuiltInServer()) {
                /** @var LocalPublicStoragePath */
                $storagePath = $container->get(LocalPublicStoragePath::class);

                $http->get(
                    $storagePath->getUrl('{path:.+}'),
                    StorageAssetAction::class,
                );
            }

            $http->group(
                '',
                function (Proxy $proxy) {

                    /** @var string */
                    $systemRoutePath = config('system.route', '/system');

                    $proxy->get(
                        '/vendor/assets/{path:.+}',
                        VendorAssetAction::class,
                    )->setName(VendorAssetAction::RouteName);

                    $proxy->group(
                        $systemRoutePath,
                        function (Proxy $proxy) {

                            $proxy->group(
                                '/install',
                                function (Proxy $proxy) {

                                    $proxy->get(
                                        '/api',
                                        [InstallController::class, 'api'],
                                    )->setName('install.api');
                                    $proxy->post(
                                        '/api',
                                        [InstallController::class, 'saveApi'],
                                    )->setName('install.api.save');

                                    $proxy->get(
                                        '/basic',
                                        [InstallController::class, 'basicSetting'],
                                    )->setName('install.basic');
                                    $proxy->post(
                                        '/basic',
                                        [InstallController::class, 'saveBasicSetting'],
                                    )->setName('install.basic.save');

                                    $proxy->get(
                                        '',
                                        [InstallController::class, 'confirm'],
                                    )->setName('install.confirm');
                                    $proxy->post(
                                        '',
                                        [InstallController::class, 'install'],
                                    )->setName('install');
                                }
                            )->add(WhenUninstalled::class);

                            $proxy->group('', function (Proxy $proxy) {

                                $proxy->group('', function (Proxy $proxy) {

                                    $proxy->group('/theme', function (Proxy $proxy) {
                                        $proxy->get(
                                            '/{id}/assets/{path:.+}',
                                            ThemeAssetAction::class,
                                        )->setName(ThemeAssetAction::RouteName);
                                    });

                                    $proxy->get(
                                        '/phpinfo',
                                        PhpInfoAction::class,
                                    )->setName('admin.phpinfo');

                                    $proxy->get(
                                        '',
                                        [DashboardController::class, 'dashboard'],
                                    )->setName('admin.dashboard');

                                    $proxy->get(
                                        '/account',
                                        [AdminAccountController::class, 'edit'],
                                    )->setName('admin.account.edit');
                                    $proxy->put(
                                        '/account',
                                        [AdminAccountController::class, 'update'],
                                    )->setName('admin.account.update');

                                    $proxy->get(
                                        '/basic',
                                        [BasicSettingController::class, 'edit'],
                                    )->setName('admin.basic.edit');

                                    $proxy->put(
                                        '/api',
                                        [MicroCmsApiController::class, 'update'],
                                    )->setName('admin.api.update');
                                    $proxy->put(
                                        '/meta',
                                        [SiteMetaController::class, 'update'],
                                    )->setName('admin.meta.update');

                                    $proxy->get(
                                        '/seo',
                                        [SiteSeoController::class, 'edit'],
                                    )->setName('admin.seo.edit');
                                    $proxy->put(
                                        '/seo',
                                        [SiteSeoController::class, 'update'],
                                    )->setName('admin.seo.update');
                                    $proxy->delete(
                                        '/seo/{target:favicon|icon}',
                                        [SiteSeoController::class, 'deleteImage'],
                                    )
                                        ->setName('admin.seo.delete.image')
                                        ->add(HtmxAccess::class);

                                    $proxy->get(
                                        '/tracking-code',
                                        [TrackingCodeController::class, 'edit'],
                                    )->setName('admin.tracking.edit');
                                    $proxy->put(
                                        '/tracking-code',
                                        [TrackingCodeController::class, 'update'],
                                    )->setName('admin.tracking.update');

                                    $proxy->get(
                                        '/robots-txt',
                                        [RobotsTxtController::class, 'edit'],
                                    )->setName('admin.robots.edit');
                                    $proxy->put(
                                        '/robots-txt',
                                        [RobotsTxtController::class, 'update'],
                                    )->setName('admin.robots.update');

                                    $proxy->patch(
                                        '/publish/{status:published|unpublished}',
                                        SitePublishAction::class,
                                    )->setName('admin.publish');

                                    $proxy->group('/theme', function (Proxy $proxy) {

                                        $proxy->group('', function (Proxy $proxy) {
                                            $proxy->get(
                                                '/active/preview[/{path:.+}]',
                                                ThemePreviewAction::class,
                                            )->setName('admin.theme.preview');

                                            $proxy->get(
                                                '/active/customization',
                                                [ThemeCustomizationController::class, 'edit'],
                                            )->setName('admin.theme.customization.edit');
                                            $proxy->put(
                                                '/active/customization/cache',
                                                [ThemeCustomizationController::class, 'cache'],
                                            )->setName('admin.theme.customization.cache');
                                            $proxy->put(
                                                '/active/customization/apply',
                                                [ThemeCustomizationController::class, 'apply'],
                                            )->setName('admin.theme.customization.apply');
                                        })->add(VerifyActiveThemeCustomizability::class);

                                        $proxy->get(
                                            '',
                                            [ThemeController::class, 'index'],
                                        )->setName('admin.theme.index');
                                        $proxy->get(
                                            '/{id}',
                                            [ThemeController::class, 'detail'],
                                        )->setName('admin.theme.detail');
                                        $proxy->put(
                                            '/{id}',
                                            [ThemeController::class, 'activate'],
                                        )->setName('admin.theme.activate');
                                        $proxy->delete(
                                            '/{id}',
                                            [ThemeController::class, 'delete'],
                                        )->setName('admin.theme.delete');
                                        $proxy->post(
                                            '/{id}/copy',
                                            [ThemeController::class, 'copy'],
                                        )->setName('admin.theme.copy');

                                        $proxy->get(
                                            '/{id}/meta',
                                            [ThemeMetaController::class, 'edit'],
                                        )->setName('admin.theme.meta.edit');
                                        $proxy->put(
                                            '/{id}/meta',
                                            [ThemeMetaController::class, 'update'],
                                        )->setName('admin.theme.meta.update');
                                    });

                                    $proxy->get(
                                        '/webhook',
                                        [WebhookController::class, 'edit'],
                                    )->setName('admin.webhook.edit');
                                    $proxy->patch(
                                        '/webhook/regenerate',
                                        [WebhookController::class, 'regenerate'],
                                    )->setName('admin.webhook.regenerate');

                                    $proxy->get(
                                        '/cache',
                                        [CacheController::class, 'confirm'],
                                    )->setName('admin.cache.confirm');
                                    $proxy->delete(
                                        '/cache',
                                        [CacheController::class, 'clean'],
                                    )->setName('admin.cache.clean');

                                    $proxy->get(
                                        '/uninstall',
                                        [UninstallController::class, 'confirm'],
                                    )->setName('admin.uninstall.confirm');
                                    $proxy->post(
                                        '/uninstall',
                                        [UninstallController::class, 'uninstall'],
                                    )->setName('admin.uninstall');

                                    $proxy->post(
                                        '/logout',
                                        [LoginController::class, 'logout'],
                                    )->setName('admin.logout');
                                })->add(AdminAuth::auth(
                                    redirect()->route('admin.login'),
                                ));

                                $proxy->group('', function (Proxy $proxy) {

                                    $proxy->get(
                                        '/login',
                                        [LoginController::class, 'form'],
                                    )->setName('admin.login');
                                    $proxy->post(
                                        '/login',
                                        [LoginController::class, 'login'],
                                    )->setName('admin.login.post');
                                })->add(AdminAuth::guest(
                                    redirect()->route('admin.dashboard'),
                                ));
                            })->add(AdminSessionStart::class)
                                ->add(GuideToInstallation::class);
                        }
                    )->add(BasicAuth::class);

                    $proxy->get(
                        '/assets/{path:.+}',
                        ActiveThemeAssetAction::class,
                    )->setName(ActiveThemeAssetAction::RouteName);

                    ThemeRouteGroupHandler::configure($proxy)
                        ->add(InsertTrackingCode::class)
                        ->add(WhenUnpublished::class)
                        ->add(GuideToInstallation::class);
                }
            )
                ->add(Csrf::class);
        },
    );
