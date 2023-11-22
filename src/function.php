<?php

// Executed after DI container dependency settings are completed.
// Here, mainly configure routing and middleware.

use CmsTool\Session\Flash\FlashSessionsContext;
use CmsTool\Session\Middleware\Csrf;
use CmsTool\Session\Middleware\SessionStart;
use CmsTool\Support\Validation\RequestValidator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouteCollectorProxyInterface as Proxy;
use Slim\Middleware\MethodOverrideMiddleware;
use Takemo101\CmsTool\Error\ErrorPageRender;
use Takemo101\Chubby\Http\ErrorHandler\ErrorResponseRenders;
use Takemo101\Chubby\Http\Renderer\RedirectRenderer;
use Takemo101\Chubby\Support\ApplicationSummary;
use Takemo101\CmsTool\Http\Action\VendorAssetAction;
use Takemo101\CmsTool\Http\Controller\InstallController;
use Takemo101\CmsTool\Http\Request\TestRequest;
use Takemo101\CmsTool\Support\Session\FlashOldInputs;
use Takemo101\CmsTool\Error\ValidationErrorResponseRender;
use Takemo101\CmsTool\Http\Middleware\GuideToInstallation;
use Takemo101\CmsTool\Http\Middleware\ValidForUninstallation;

$hook = hook();

$hook
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
        function (ServerRequestInterface $request) {
            $inputs = FlashSessionsContext::fromServerRequest($request)
                ->getFlashSessions()
                ->get(FlashOldInputs::class);

            /** @var array<string,mixed> */
            $params = [
                ...$request->getQueryParams(),
                ...(array) $request->getParsedBody(),
            ];

            if (!empty($params)) {
                $inputs->put($params);
            }
        }
    );

$http = http();

$http->add(Csrf::class);
$http->add(SessionStart::class);
$http->add(new MethodOverrideMiddleware());

$http->group('', function (Proxy $proxy) {
    $proxy->get(
        '/',
        function () {
            return view('cms-tool::home');
        },
    )->setName('home');

    $proxy->post(
        '/create',
        function (
            ServerRequestInterface $request,
            RequestValidator $validator,
        ) {
            $form = $validator->throwIfFailed(
                $request,
                TestRequest::class,
            );

            return new RedirectRenderer(
                route('vendor-asset', ['path' => 'example.jpeg'])
            );
        },
    )->setName('create');
})->add(GuideToInstallation::class);

$http->get(
    '/assets/{path:.*}',
    VendorAssetAction::class,
)->setName('vendor-asset');

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
                    '/meta',
                    [InstallController::class, 'metaPage'],
                )->setName('install.meta');
                $proxy->post(
                    '/meta',
                    [InstallController::class, 'saveMeta'],
                )->setName('install.meta.save');

                $proxy->get(
                    '/account',
                    [InstallController::class, 'accountPage'],
                )->setName('install.account');
                $proxy->post(
                    '/account',
                    [InstallController::class, 'saveAccount'],
                )->setName('install.account.save');

                $proxy->get(
                    '/confirm',
                    [InstallController::class, 'confirmPage'],
                )->setName('install.confirm');
                $proxy->post(
                    '/',
                    [InstallController::class, 'installed'],
                )->setName('installed');
            }
        );
    }
)->add(ValidForUninstallation::class);
