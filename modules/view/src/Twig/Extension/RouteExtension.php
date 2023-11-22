<?php

namespace CmsTool\View\Twig\Extension;

use Slim\Interfaces\RouteParserInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouteExtension extends AbstractExtension
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
     * @return TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'route',
                [$this->routeParser, 'urlFor'],
            )
        ];
    }
}
