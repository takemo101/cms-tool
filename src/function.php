<?php

// Executed after DI container dependency settings are completed.
// Here, mainly configure routing and middleware.

use CmsTool\Session\Middleware\Csrf;
use CmsTool\Session\Middleware\SessionStart;
use Psr\Container\ContainerInterface;
use Slim\Interfaces\RouteCollectorProxyInterface as Proxy;
use Takemo101\Chubby\Console\CommandCollection;
use Takemo101\Chubby\Http\ErrorHandler\ErrorResponseRenders;
use Takemo101\Chubby\Http\SlimHttpAdapter;
use Takemo101\Chubby\Support\ApplicationSummary;
use Takemo101\CmsTool\Console\StorageLinkCommand;
use Takemo101\CmsTool\Error\SystemErrorPageRender;
use Takemo101\CmsTool\Error\ThemeErrorPageRender;
use Takemo101\CmsTool\Http\Action\VendorAssetAction;
use Takemo101\CmsTool\Http\Controller\InstallController;
use Takemo101\CmsTool\Error\ValidationErrorResponseRender;
use Takemo101\CmsTool\Http\Action\PhpInfoAction;
use Takemo101\CmsTool\Http\Action\SitePublishAction;
use Takemo101\CmsTool\Http\Action\Theme\ActiveThemeAssetAction;
use Takemo101\CmsTool\Http\Action\Theme\HomeAction;
use Takemo101\CmsTool\Http\Action\Theme\FixedPageAction;
use Takemo101\CmsTool\Http\Action\WebhookAction;
use Takemo101\CmsTool\Http\Action\ThemeAssetAction;
use Takemo101\CmsTool\Http\Controller\Admin\AdminAccountController;
use Takemo101\CmsTool\Http\Controller\Admin\BasicSettingController;
use Takemo101\CmsTool\Http\Controller\Admin\DashboardController;
use Takemo101\CmsTool\Http\Controller\Admin\LoginController;
use Takemo101\CmsTool\Http\Controller\Admin\MicroCmsApiController;
use Takemo101\CmsTool\Http\Controller\Admin\SiteMetaController;
use Takemo101\CmsTool\Http\Controller\Admin\SiteSeoController;
use Takemo101\CmsTool\Http\Controller\Admin\ThemeController;
use Takemo101\CmsTool\Http\Controller\Admin\Tool\ThemeJsonController;
use Takemo101\CmsTool\Http\Controller\Admin\UninstallController;
use Takemo101\CmsTool\Http\Controller\Admin\WebhookController;
use Takemo101\CmsTool\Http\Middleware\AdminAuth;
use Takemo101\CmsTool\Http\Middleware\AdminSessionStart;
use Takemo101\CmsTool\Http\Middleware\GuideToInstallation;
use Takemo101\CmsTool\Http\Middleware\WhenUninstalled;
use Takemo101\CmsTool\Http\Middleware\WhenUnpublished;
use Takemo101\CmsTool\Support\Theme\ActiveThemeRouteRegister;

hook()
    ->onTyped(
        fn (CommandCollection $commands) => $commands
            ->add(StorageLinkCommand::class),
    )
    ->onTyped(
        function (ErrorResponseRenders $renders, ContainerInterface $container) {
            /** @var ApplicationSummary */
            $summary = $container->get(ApplicationSummary::class);

            $renders->addRender(
                new ValidationErrorResponseRender(),
            );

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
        },
    )
    ->onTyped(
        function (SlimHttpAdapter $http) {

            /** @var string */
            $webhookRoutePath = config('system.webhook.route', '/webhook');

            $http->add(SessionStart::class);

            $http->post(
                $webhookRoutePath,
                WebhookAction::class,
            )->setName('webhook');

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
                                        'phpinfo',
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
                                    )->setName('admin.seo.delete.image');

                                    $proxy->patch(
                                        '/publish/{status:published|unpublished}',
                                        SitePublishAction::class,
                                    )->setName('admin.publish');

                                    $proxy->get(
                                        '/theme',
                                        [ThemeController::class, 'index'],
                                    )->setName('admin.theme.index');
                                    $proxy->get(
                                        '/theme/{id}',
                                        [ThemeController::class, 'detail'],
                                    )->setName('admin.theme.detail');
                                    $proxy->put(
                                        '/theme/{id}',
                                        [ThemeController::class, 'activate'],
                                    )->setName('admin.theme.activate');

                                    $proxy->get(
                                        '/webhook',
                                        [WebhookController::class, 'edit'],
                                    )->setName('admin.webhook.edit');
                                    $proxy->patch(
                                        '/webhook/regenerate',
                                        [WebhookController::class, 'regenerate'],
                                    )->setName('admin.webhook.regenerate');

                                    $proxy->get(
                                        '/uninstall',
                                        [UninstallController::class, 'confirm'],
                                    )->setName('admin.uninstall.confirm');
                                    $proxy->post(
                                        '/uninstall',
                                        [UninstallController::class, 'uninstall'],
                                    )->setName('admin.uninstall');

                                    $proxy->group(
                                        '/tool',
                                        function (Proxy $proxy) {
                                            $proxy->get(
                                                '/theme-json',
                                                [ThemeJsonController::class, 'edit'],
                                            )->setName('admin.tool.theme-json.edit');

                                            $proxy->post(
                                                '/theme-json',
                                                [ThemeJsonController::class, 'generate'],
                                            )->setName('admin.tool.theme-json.generate');
                                        }
                                    );

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
                    );

                    $proxy->group('', function (Proxy $proxy) {

                        /** @var ActiveThemeRouteRegister */
                        $register = container()->get(ActiveThemeRouteRegister::class);

                        $proxy->get(
                            '/',
                            HomeAction::class,
                        )->setName('home');

                        $proxy->get(
                            '/assets/{path:.+}',
                            ActiveThemeAssetAction::class,
                        )->setName(ActiveThemeAssetAction::RouteName);

                        // Set routing for theme
                        $register->register($proxy);

                        $proxy->get(
                            '/{path:.+}',
                            FixedPageAction::class,
                        )->setName('fixed-page');
                    })
                        ->add(WhenUnpublished::class)
                        ->add(GuideToInstallation::class);
                }
            )
                ->add(Csrf::class);
        },
    );
