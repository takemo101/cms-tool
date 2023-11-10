<?php

use Slim\Interfaces\RouteParserInterface;

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
