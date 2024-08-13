<?php

namespace Takemo101\CmsTool\Preset\Shared\Feed;

use CmsTool\Support\Feed\FeedGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Http\Renderer\ResponseRenderer;

/**
 * ResponseRenderer for rendering feed responses.
 */
class FeedActionAndResponseRenderer implements ResponseRenderer
{
    private ?FeedCreator $creator = null;

    /**
     * constructor
     *
     * @param FeedGenerator $generator
     * @param ApplicationContainer $container
     */
    public function __construct(
        private readonly FeedGenerator $generator,
        private readonly ApplicationContainer $container,
    ) {
        //
    }

    /**
     * Set the feed creator.
     *
     * @param FeedCreator $creator
     * @return void
     */
    public function setCreator(FeedCreator $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * Check if the feed creator is set.
     *
     * @return bool
     */
    public function hasCreator(): bool
    {
        return $this->creator !== null;
    }

    /**
     * {@inheritDoc}
     *
     * Feed response processing requires setting the FeedCreator class before execution.
     *
     * @throws HttpNotFoundException
     */
    public function render(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {

        $creator = $this->creator;

        // If the feed creator is not set, throw an exception.
        if ($creator === null) {
            throw new HttpNotFoundException($request, 'The feed creator is not set');
        }

        $feed = $creator->create(
            request: $request,
            container: $this->container,
        );

        $response = $response->withHeader(
            'Content-Type',
            $this->generator->getOutputMeta()
                ->contentType
        );

        $response->getBody()->write(
            $this->generator->generate($feed),
        );

        return $response;
    }

    /**
     * Action for creating a feed response.
     *
     * @return self
     */
    public function __invoke(): self
    {
        return $this;
    }
}
