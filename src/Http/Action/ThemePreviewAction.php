<?php

namespace Takemo101\CmsTool\Http\Action;

use CmsTool\Theme\ActiveTheme;
use CmsTool\View\Support\RouteParserDecorator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\Factory\SlimFactory;
use Takemo101\CmsTool\Http\Routing\ThemeRouteGroupHandler;
use Takemo101\CmsTool\Support\Accessor\ThemeCustomizationPreview;
use Takemo101\CmsTool\UseCase\Theme\Support\ThemeCustomizationTemporaryCache;

class ThemePreviewAction
{
    /**
     * constructor
     *
     * @param SlimFactory $factory
     * @param RouteParserDecorator $routeParser
     * @param ThemeCustomizationTemporaryCache $cache
     * @param ActiveTheme $activeTheme
     */
    public function __construct(
        private readonly SlimFactory $factory,
        private readonly RouteParserDecorator $routeParser,
        private readonly ThemeCustomizationTemporaryCache $cache,
        private readonly ActiveTheme $activeTheme,
    ) {
        //
    }

    /**
     * Handle theme preview
     *
     * @param ServerRequestInterface $request
     * @param ThemeCustomizationPreview $preview
     * @param string|null $path
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ThemeCustomizationPreview $preview,
        ?string $path = null,
    ): ResponseInterface {
        $slim = $this->factory->create();

        $slim->setBasePath(
            $this->extractBasePath(
                request: $request,
                suffixPath: $path,
            ),
        );

        ThemeRouteGroupHandler::configure($slim);

        $slim->addRoutingMiddleware();

        $this->routeParser->changeRouteParser(
            $slim->getRouteCollector()->getRouteParser(),
        );

        // If there is a cache, set it as preview data.
        if ($data = $this->cache->get($this->activeTheme->id)) {
            $preview->set(
                $this->activeTheme->refineCustomizationWithDefaults($data),
            );
        }

        return $slim->handle($request);
    }

    /**
     * Extract base path from request.
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
