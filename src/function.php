<?php

// Executed after DI container dependency settings are completed.
// Here, mainly configure routing and middleware.

use CmsTool\Session\Middleware\Csrf;
use CmsTool\Session\Middleware\SessionStart;
use Psr\Container\ContainerInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Takemo101\CmsTool\Error\ErrorPageRender;
use Takemo101\Chubby\Http\ErrorHandler\ErrorResponseRenders;
use Takemo101\Chubby\Support\ApplicationSummary;
use Takemo101\CmsTool\Http\Action\VendorAssetAction;
use Takemo101\CmsTool\Http\Controller\InstallController;

$hook = hook();

$hook->onByType(
    function (ErrorResponseRenders $renders, ContainerInterface $container) {
        /** @var ApplicationSummary */
        $summary = $container->get(ApplicationSummary::class);

        if (!$summary->isDebugMode()) {
            $renders->addRender(
                new ErrorPageRender(),
            );
        }
    },
);

$http = http();

$http->add(Csrf::class);
$http->add(SessionStart::class);

$http->get(
    '/',
    function () {
        return view('home');
    },
)->setName('home');

$http->get(
    '/assets/{path:.*}',
    VendorAssetAction::class,
)->setName('vendor-asset');

$http->group(
    '/system/install',
    function (RouteCollectorProxyInterface $proxy) {
        $proxy->get(
            '/api',
            [InstallController::class, 'api'],
        );
        $proxy->post(
            '/api',
            [InstallController::class, 'saveApi'],
        );

        $proxy->get(
            '/meta',
            [InstallController::class, 'meta'],
        );
        $proxy->post(
            '/meta',
            [InstallController::class, 'saveMeta'],
        );

        $proxy->get(
            '/account',
            [InstallController::class, 'account'],
        );
        $proxy->post(
            '/account',
            [InstallController::class, 'saveAccount'],
        );

        $proxy->get(
            '/confirm',
            [InstallController::class, 'confirm'],
        );
        $proxy->post(
            '/complete',
            [InstallController::class, 'complete'],
        );
    }
);
