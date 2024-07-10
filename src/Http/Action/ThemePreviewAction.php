<?php

namespace Takemo101\CmsTool\Http\Action;

use CmsTool\View\Support\RouteParserDecorator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\Factory\SlimFactory;
use Takemo101\CmsTool\Http\Routing\ThemeRouteGroupHandler;

class ThemePreviewAction
{
    /**
     * Handle theme preview
     *
     * @param ServerRequestInterface $request
     * @param SlimFactory $factory
     * @param RouteParserDecorator $routeParser
     * @param string|null $path
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        SlimFactory $factory,
        RouteParserDecorator $routeParser,
        ?string $path = null,
    ): ResponseInterface {
        $slim = $factory->create();

        $slim->setBasePath(
            $this->extractBasePath(
                request: $request,
                suffixPath: $path,
            ),
        );

        ThemeRouteGroupHandler::configure($slim);

        $slim->addRoutingMiddleware();

        $routeParser->changeRouteParser(
            $slim->getRouteCollector()->getRouteParser(),
        );

        return $slim->handle($request);
    }

    /**
     * Extract base path from request
     *
     * @param ServerRequestInterface $request
     * @param string|null $suffixPath
     * @return string
     */
    private function extractBasePath(
        ServerRequestInterface $request,
        ?string $suffixPath = null,
    ): string {
        $basePath = $suffixPath
            ? str_replace(
                $suffixPath,
                '',
                $request->getUri()->getPath(),
            )
            : $request->getUri()->getPath();

        return rtrim($basePath, '/');
    }
}