<?php

namespace Takemo101\CmsTool\Http\Middleware;

use CmsTool\View\Accessor\DataAccessors;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Takemo101\CmsTool\Support\Accessor\ServerRequestAccessor;

class Setup implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param DataAccessors $accessors
     */
    public function __construct(
        private DataAccessors $accessors,
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

        $this->setupAccessors($request);

        return $handler->handle($request);
    }

    private function setupAccessors(
        ServerRequestInterface $request
    ): void {
        // Make a request from View via Dataaccessors
        $this->accessors->add(
            'request',
            ServerRequestAccessor::class,
            [
                'request' => $request,
            ]
        );
    }
}
