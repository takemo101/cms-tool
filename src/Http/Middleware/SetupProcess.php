<?php

namespace Takemo101\CmsTool\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Takemo101\Chubby\Event\EventDispatcher;
use Takemo101\CmsTool\Infra\Event\Setup;

class SetupProcess implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param EventDispatcher $dispatcher
     */
    public function __construct(
        private EventDispatcher $dispatcher,
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

        $this->dispatcher->dispatch(new Setup($request));

        return $handler->handle($request);
    }
}
