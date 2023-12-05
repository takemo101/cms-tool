<?php

namespace Takemo101\CmsTool\Support;

use Takemo101\Chubby\Http\Renderer\RedirectRenderer;
use Takemo101\Chubby\Http\Renderer\RouteRedirectRenderer;
use Takemo101\CmsTool\Http\Renderer\RedirectBackRenderer;

class RedirectRendererFactory
{
    /**
     * Create a redirect renderer by URL
     *
     * @param string $url
     * @return RedirectRenderer
     */
    public function url(string $url): RedirectRenderer
    {
        return new RedirectRenderer($url);
    }

    /**
     * Create a redirect renderer to redirect back
     *
     * @return RedirectBackRenderer
     */
    public function back(): RedirectBackRenderer
    {
        return new RedirectBackRenderer();
    }

    /**
     * Create a redirect renderer by route
     *
     * @param string $route
     * @param array<string,string> $data
     * @param array<string,string> $query
     * @return RouteRedirectRenderer
     */
    public function route(
        string $route,
        array $data = [],
        array $query = [],
    ): RouteRedirectRenderer {
        return new RouteRedirectRenderer(
            route: $route,
            data: $data,
            query: $query,
        );
    }
}
