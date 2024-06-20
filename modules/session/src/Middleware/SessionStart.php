<?php

namespace CmsTool\Session\Middleware;

use CmsTool\Session\Contract\SessionFactory;
use CmsTool\Session\Event\SessionStarted;
use CmsTool\Session\Flash\FlashSessions;
use CmsTool\Session\Flash\FlashSessionsContext;
use CmsTool\Session\Flash\FlashSessionsFactory;
use CmsTool\Session\SessionContext;
use CmsTool\Session\SessionHookTags;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Takemo101\Chubby\Hook\Hook;

class SessionStart implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param SessionFactory $sessionFactory
     * @param FlashSessionsFactory $flashSessionsFactory
     * @param EventDispatcherInterface $dispatcher
     * @param Hook $hook
     */
    public function __construct(
        private readonly SessionFactory $sessionFactory,
        private readonly FlashSessionsFactory $flashSessionsFactory,
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

        $session = $this->sessionFactory->create();

        if (!$session->isStarted()) {
            $session->start();
        }

        $flashSessions = $this->flashSessionsFactory->create($session);

        $sessionContext = new SessionContext($session);
        $flashSessionsContext = new FlashSessionsContext($flashSessions);

        $request = $flashSessionsContext->withRequest(
            $sessionContext->withRequest($request),
        );

        $this->hook->do(
            tag: SessionInterface::class,
            parameter: $session,
        );

        $this->hook->do(
            tag: SessionManagerInterface::class,
            parameter: $session,
        );

        $this->hook->do(
            tag: FlashSessions::class,
            parameter: $flashSessions,
        );

        $this->hook->do(
            tag: SessionHookTags::SessionStarted,
            parameter: $session,
        );

        $this->hook->do(
            tag: SessionHookTags::FlashSessionStarted,
            parameter: $flashSessions,
        );

        $this->dispatcher->dispatch(
            new SessionStarted(
                request: $request,
                session: $session,
                flashSessions: $flashSessions,
            ),
        );

        $response = $handler->handle($request);

        $session->save();

        return $response;
    }
}
