<?php

// Executed after DI container dependency settings are completed.
// Here, mainly configure routing and middleware.

use Psr\Container\ContainerInterface;
use Takemo101\CmsTool\Error\ErrorPageRender;
use Takemo101\Chubby\Http\ErrorHandler\ErrorResponseRenders;
use Takemo101\Chubby\Support\ApplicationSummary;
use Takemo101\CmsTool\Action\VendorAssetAction;

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
