<?php

namespace CmsTool\View\Support;

use Psr\Http\Message\UriInterface;
use Slim\Interfaces\RouteParserInterface;

class RouteParserDecorator implements RouteParserInterface
{
    /**
     * constructor
     *
     * @param RouteParserInterface $routeParser
     */
    public function __construct(
        private RouteParserInterface $routeParser,
    ) {
        //
    }

    /**
     * Change the route parser
     *
     * @param RouteParserInterface $routeParser
     * @return void
     */
    public function changeRouteParser(RouteParserInterface $routeParser)
    {
        $this->routeParser = $routeParser;
    }

    /**
     * {@inheritdoc}
     */
    public function relativeUrlFor(string $routeName, array $data = [], array $queryParams = []): string
    {
        return $this->routeParser->relativeUrlFor($routeName, $data, $queryParams);
    }

    /**
     * {@inheritdoc}
     */
    public function urlFor(string $routeName, array $data = [], array $queryParams = []): string
    {
        return $this->routeParser->urlFor($routeName, $data, $queryParams);
    }

    /**
     * {@inheritdoc}
     */
    public function fullUrlFor(UriInterface $uri, string $routeName, array $data = [], array $queryParams = []): string
    {
        return $this->routeParser->fullUrlFor($uri, $routeName, $data, $queryParams);
    }
}
