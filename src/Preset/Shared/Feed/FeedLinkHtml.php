<?php

namespace Takemo101\CmsTool\Preset\Shared\Feed;

use CmsTool\Support\Feed\FeedGenerator;
use CmsTool\View\Contract\Htmlable;
use Slim\Interfaces\RouteParserInterface;

class FeedLinkHtml implements Htmlable
{
    /**
     * constructor
     *
     * @param FeedActionAndResponseRenderer $renderer
     * @param FeedGenerator $generator
     * @param RouteParserInterface $routeParser
     */
    public function __construct(
        private readonly FeedActionAndResponseRenderer $renderer,
        private readonly FeedGenerator $generator,
        private readonly RouteParserInterface $routeParser,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->renderer->hasCreator()
            ? sprintf(
                '<link rel="alternate" type="%s" href="%s">',
                $this->generator->getOutputMeta()->mimeType,
                $this->routeParser->urlFor('feed'),
            )
            : '';
    }
}
