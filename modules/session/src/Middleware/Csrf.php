<?php

namespace CmsTool\Session\Middleware;

use CmsTool\Session\Csrf\CsrfGuard;
use CmsTool\Session\Csrf\CsrfGuardContext;
use CmsTool\Session\Csrf\CsrfGuardFactory;
use CmsTool\Session\Event\CsrfGuardStarted;
use CmsTool\Session\SessionContext;
use CmsTool\Session\SessionHookTags;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Takemo101\Chubby\Hook\Hook;

/**
 * To execute this middleware, you need to execute the Sessionstart middleware first.
 */
class Csrf implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param CsrfGuardFactory $factory
     * @param EventDispatcherInterface $dispatcher
     * @param Hook $hook
     */
    public function __construct(
        private readonly CsrfGuardFactory $factory,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly Hook $hook,
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

        $request = $context->withRequest($request);

        $this->hook->do(
            CsrfGuard::class,
            $guard,
        );

        $this->hook->do(
            SessionHookTags::CsrfGuardStarted,
            $guard,
        );

        $this->dispatcher->dispatch(
            new CsrfGuardStarted(
                request: $request,
                guard: $guard,
            ),
        );

        return $guard->process(
            $request,
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
