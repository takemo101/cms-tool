<?php

use Slim\Interfaces\RouteCollectorProxyInterface;
use Takemo101\Chubby\Http\Renderer\HtmlRenderer;

use const Takemo101\CmsTool\HookTags\RegisterThemeRoute;

hook()->on(
    RegisterThemeRoute,
    function (
        RouteCollectorProxyInterface $proxy,
    ) {
        $proxy->get(
            '/sample',
            fn () => new HtmlRenderer(
                <<<HTML
                    <h1>Sample</h1>
                HTML,
            ),
        );
    },
);
