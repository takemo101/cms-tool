<?php

namespace Takemo101\CmsTool\Http\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\Factory\SlimFactory;
use Takemo101\Chubby\Http\Renderer\StaticRenderer;
use Takemo101\CmsTool\Http\Routing\ThemeRouteGroupHandler;

class ThemePreviewAction
{
    /**
     * Handle theme preview
     *
     * @param ServerRequestInterface $request
     * @param SlimFactory $factory
     * @param string $path
     * @return StaticRenderer
     */
    public function __invoke(
        ServerRequestInterface $request,
        SlimFactory $factory,
        string $path = '',
    ): ResponseInterface {
        $slim = $factory->create();

        ThemeRouteGroupHandler::configure($slim);

        $slim->addRoutingMiddleware();

        return $slim->handle(
            $request->withUri(
                $request->getUri()->withPath($path),
            ),
        );
    }
}
