<?php

namespace Takemo101\CmsTool\Infra\Event;

use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Event\StoppableEvent;
use Takemo101\CmsTool\UseCase\Admin\Auth\AdminSession;

class AdminSessionStarted extends StoppableEvent
{
    /**
     * constructor
     *
     * @param ServerRequestInterface $request
     * @param AdminSession $admin
     */
    public function __construct(
        private readonly ServerRequestInterface $request,
        private readonly AdminSession $admin,
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
     * Get the administrator session.
     *
     * @return AdminSession
     */
    public function getAdminSession(): AdminSession
    {
        return $this->admin;
    }
}
