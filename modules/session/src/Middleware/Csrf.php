<?php

namespace CmsTool\Session\Middleware;

use CmsTool\Session\Csrf\CsrfGuard;
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
class Csrf implements MiddlewareInterface
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

        $sessionContext = SessionContext::fromRequest($request);

        // If there is no session, do not check the CSRF
        if (!$sessionContext) {
            return $handler->handle($request);
        }

        $session = $sessionContext->getSession();

        $guard = $this->factory->create($session);

        $context = new CsrfGuardContext($guard);

        $request = $this->withToken($request, $guard);

        return $guard->process(
            $context->withRequest($request),
            $handler,
        );
    }

    private function withToken(
        ServerRequestInterface $request,
        CsrfGuard $guard,
    ): ServerRequestInterface {

        $tokenName = $request->getHeader($guard->getHeaderTokenNameKey())[0] ?? false;
        $tokenValue = $request->getHeader($guard->getHeaderTokenValueKey())[0] ?? false;

        if ($tokenName && $tokenValue) {
            return $request->withParsedBody([
                $guard->getTokenNameKey() => $tokenName,
                $guard->getTokenValueKey() => $tokenValue,
            ]);
        }

        return $request;
    }
}
