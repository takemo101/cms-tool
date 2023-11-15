<?php

namespace CmsTool\Session\Middleware;

use CmsTool\Session\Csrf\CsrfGuardContext;
use CmsTool\Session\Csrf\CsrfGuardFactory;
use CmsTool\Session\SessionContext;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * To execute this middleware, you need to execute the Sessionstart middleware first.
 */
final readonly class Csrf implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param CsrfGuardFactory $factory
     */
    public function __construct(
        private CsrfGuardFactory $factory,
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

        $session = SessionContext::fromServerRequest($request)
            ->getSession();

        $guard = $this->factory->create($session);

        $context = new CsrfGuardContext($guard);

        return $guard->process(
            $context->withContext($request),
            $handler,
        );
    }
}
