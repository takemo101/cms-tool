<?php

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\RouteCollectorProxyInterface as Proxy;
use Takemo101\CmsTool\HookTags;

hook()->on(
    HookTags::RegisterThemeRoute,
    function (
        Proxy $proxy,
    ) {
        $proxy->get(
            '/sample',
            fn (
                ServerRequestInterface $request,
            ) => throw new HttpNotFoundException($request),
        );
    },
);
