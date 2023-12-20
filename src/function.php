<?php

// Executed after DI container dependency settings are completed.
// Here, mainly configure routing and middleware.

use CmsTool\Session\Middleware\Csrf;
use CmsTool\Session\Middleware\SessionStart;
use Psr\Container\ContainerInterface;
use Slim\Interfaces\RouteCollectorProxyInterface as Proxy;
use Takemo101\Chubby\Console\CommandCollection;
use Takemo101\CmsTool\Error\ErrorPageRender;
use Takemo101\Chubby\Http\ErrorHandler\ErrorResponseRenders;
use Takemo101\Chubby\Http\SlimHttpAdapter;
use Takemo101\Chubby\Support\ApplicationSummary;
use Takemo101\CmsTool\Console\StorageLinkCommand;
use Takemo101\CmsTool\Http\Action\VendorAssetAction;
use Takemo101\CmsTool\Http\Controller\InstallController;
use Takemo101\CmsTool\Error\ValidationErrorResponseRender;
use Takemo101\CmsTool\Http\Action\PhpInfoAction;
use Takemo101\CmsTool\Http\Action\SitePublishAction;
use Takemo101\CmsTool\Http\Action\Theme\AssetAction;
use Takemo101\CmsTool\Http\Action\Theme\HomeAction;
use Takemo101\CmsTool\Http\Action\Theme\FixedPageAction;
use Takemo101\CmsTool\Http\Action\ThemeAssetAction;
use Takemo101\CmsTool\Http\Controller\Admin\AdminAccountController;
use Takemo101\CmsTool\Http\Controller\Admin\BasicSettingController;
use Takemo101\CmsTool\Http\Controller\Admin\DashboardController;
use Takemo101\CmsTool\Http\Controller\Admin\LoginController;
use Takemo101\CmsTool\Http\Controller\Admin\MicroCmsApiController;
use Takemo101\CmsTool\Http\Controller\Admin\SiteMetaController;
use Takemo101\CmsTool\Http\Controller\Admin\SiteSeoController;
use Takemo101\CmsTool\Http\Middleware\AdminAuth;
use Takemo101\CmsTool\Http\Middleware\AdminSessionStart;
use Takemo101\CmsTool\Http\Middleware\GuideToInstallation;
use Takemo101\CmsTool\Http\Middleware\ValidForUninstallation;
use Takemo101\CmsTool\Support\Theme\ActiveThemeFunctionLoader;
use Takemo101\CmsTool\Support\Theme\ActiveThemeRouteRegister;

hook()
    ->onByType(
        fn (CommandCollection $commands) => $commands
            ->add(StorageLinkCommand::class),
    )
    ->onByType(
        function (ErrorResponseRenders $renders, ContainerInterface $container) {
            /** @var ApplicationSummary */
            $summary = $container->get(ApplicationSummary::class);

            $renders->addRender(
                new ValidationErrorResponseRender(),
            );

            if (!$summary->isDebugMode()) {
                $renders->addRender(
                    new ErrorPageRender(),
                );
            }
        },
    )
    ->onByType(
        function (SlimHttpAdapter $http, ContainerInterface $container) {

            /** @var ActiveThemeRouteRegister */
            $register = $container->get(ActiveThemeRouteRegister::class);

            $http->add(Csrf::class);
            $http->add(SessionStart::class);

            $http->get(
                '/vendor/assets/{path:.+}',
                VendorAssetAction::class,
            )->setName(VendorAssetAction::RouteName);

            $http->group(
                '/system',
                function (Proxy $proxy) {

                    $proxy->group(
                        '/install',
                        function (Proxy $proxy) {

                            $proxy->get(
                                '/api',
                                [InstallController::class, 'apiPage'],
                            )->setName('install.api');
                            $proxy->post(
                                '/api',
                                [InstallController::class, 'saveApi'],
                            )->setName('install.api.save');

                            $proxy->get(
                                '/basic',
                                [InstallController::class, 'basicSettingPage'],
                            )->setName('install.basic');
                            $proxy->post(
                                '/basic',
                                [InstallController::class, 'saveBasicSetting'],
                            )->setName('install.basic.save');

                            $proxy->get(
                                '',
                                [InstallController::class, 'confirmPage'],
                            )->setName('install.confirm');
                            $proxy->post(
                                '',
                                [InstallController::class, 'installed'],
                            )->setName('installed');
                        }
                    )->add(ValidForUninstallation::class);

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
                                [DashboardController::class, 'dashboardPage'],
                            )->setName('admin.dashboard');

                            $proxy->get(
                                '/account',
                                [AdminAccountController::class, 'editPage'],
                            )->setName('admin.account.edit');
                            $proxy->put(
                                '/account',
                                [AdminAccountController::class, 'update'],
                            )->setName('admin.account.update');

                            $proxy->get(
                                '/basic',
                                [BasicSettingController::class, 'editPage'],
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
                                [SiteSeoController::class, 'editPage'],
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
                                [LoginController::class, 'loginPage'],
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

            $http->group('', function (Proxy $proxy) use ($register) {
                $proxy->get(
                    '/assets/{path:.+}',
                    AssetAction::class,
                )->setName(AssetAction::RouteName);

                $proxy->get(
                    '/',
                    HomeAction::class,
                )->setName('home');

                // Set routing for theme
                $register->register($proxy);

                $proxy->get(
                    '/{path:.+}',
                    FixedPageAction::class,
                )->setName('fixed-page');
            })->add(GuideToInstallation::class);
        }
    );
