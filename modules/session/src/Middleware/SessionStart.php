<?php

namespace CmsTool\Session\Middleware;

use CmsTool\Session\Contract\SessionFactory;
use CmsTool\Session\SessionContext;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class SessionStart implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param SessionFactory $factory
     */
    public function __construct(
        private SessionFactory $factory,
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

        $session = $this->factory->create();

        if (!$session->isStarted()) {
            $session->start();
        }

        $context = new SessionContext($session);

        $response = $handler->handle(
            $context->withContext($request),
        );

        $session->save();

        return $response;
    }
}
