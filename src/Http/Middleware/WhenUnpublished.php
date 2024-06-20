<?php

namespace Takemo101\CmsTool\Http\Middleware;

use CmsTool\View\ViewCreator;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Takemo101\Chubby\Http\Renderer\HtmlRenderer;
use Takemo101\CmsTool\Domain\Publish\SitePublishRepository;

class WhenUnpublished implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param SitePublishRepository $repository
     * @param ResponseFactoryInterface $responseFactory
     * @param ViewCreator $creator
     */
    public function __construct(
        private SitePublishRepository $repository,
        private ResponseFactoryInterface $responseFactory,
        private ViewCreator $creator,
    ) {
        //
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {

        if (!$this->repository->isPublished()) {
            return (new HtmlRenderer(
                $this->creator->createIfExists(
                    'pages.error.unpublished',
                ) ?? $this->creator->create('cms-tool::error.unpublished'),
                StatusCodeInterface::STATUS_UNAUTHORIZED,
            ))
                ->render(
                    $request,
                    $this->responseFactory->createResponse(),
                );
        }

        return $handler->handle($request);
        ;
    }
}
