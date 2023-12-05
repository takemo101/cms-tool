<?php

use Slim\Interfaces\RouteParserInterface;
use Takemo101\Chubby\Http\Renderer\RedirectRenderer;
use Takemo101\CmsTool\Support\RedirectRendererFactory;

if (!function_exists('route')) {
    /**
     * Obtain a URI path from the named route.
     *
     * @param string $name
     * @param array<string,mixed> $data
     * @param array<string,mixed> $query
     * @return string
     */
    function route(string $name, array $data = [], array $query = []): string
    {
        /** @var RouteParserInterface */
        $route = container()->get(RouteParserInterface::class);

        return $route->urlFor($name, $data, $query);
    }
}

if (!function_exists('redirect')) {
    /**
     * Create a redirect renderer
     *
     * @return RedirectRendererFactory|RedirectRenderer
     */
    function redirect(?string $url = null): RedirectRendererFactory|RedirectRenderer
    {
        $factory = new RedirectRendererFactory();

        return $url
            ? $factory->url($url)
            : $factory;
    }
}
