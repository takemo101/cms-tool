<?php

namespace CmsTool\Session\Middleware;

use CmsTool\Session\Contract\SessionFactory;
use CmsTool\Session\Flash\FlashSessionsContext;
use CmsTool\Session\Flash\FlashSessionsFactory;
use CmsTool\Session\SessionContext;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionStart implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param SessionFactory $sessionFactory
     * @param FlashSessionsFactory $flashSessionsFactory
     */
    public function __construct(
        private SessionFactory $sessionFactory,
        private FlashSessionsFactory $flashSessionsFactory,
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

        $response = $handler->handle(
            $flashSessionsContext->withContext(
                $sessionContext->withContext($request),
            ),
        );

        $session->save();

        return $response;
    }
}
