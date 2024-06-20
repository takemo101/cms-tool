<?php

namespace CmsTool\Session\Event;

use CmsTool\Session\Csrf\CsrfGuard;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This event occurs when the CSRF guard is started.
 */
class CsrfGuardStarted
{
    /**
     * constructor
     *
     * @param ServerRequestInterface $request
     * @param CsrfGuard
     */
    public function __construct(
        private readonly ServerRequestInterface $request,
        private readonly CsrfGuard $guard,
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
     * Get the CSRF guard.
     *
     * @return CsrfGuard
     */
    public function getGuard(): CsrfGuard
    {
        return $this->guard;
    }
}
