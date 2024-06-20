<?php

namespace CmsTool\Session\Event;

use CmsTool\Session\Flash\FlashSessions;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This event occurs when the session is started.
 */
class SessionStarted
{
    /**
     * constructor
     *
     * @param ServerRequestInterface $request
     * @param SessionInterface & SessionManagerInterface $session
     * @param FlashSessions $flashSessions
     */
    public function __construct(
        private readonly ServerRequestInterface $request,
        private readonly SessionInterface & SessionManagerInterface $session,
        private readonly FlashSessions $flashSessions,
    ) {
        //
    }

    /**
     * Get the request.
     *
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * Get the session.
     *
     * @return SessionInterface & SessionManagerInterface
     */
    public function getSession(): SessionInterface & SessionManagerInterface
    {
        return $this->session;
    }

    /**
     * Get the flash sessions.
     *
     * @return FlashSessions
     */
    public function getFlashSessions(): FlashSessions
    {
        return $this->flashSessions;
    }
}
