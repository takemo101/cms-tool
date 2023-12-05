<?php

namespace Takemo101\CmsTool\Http\Session;

use CmsTool\Session\SessionContext;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\CmsTool\UseCase\Admin\Auth\AdminSession;

class DefaultAdminSessionFactory implements AdminSessionFactory
{
    /**
     * Create a session object
     *
     *
     * @return AdminSession
     */
    public function create(ServerRequestInterface $request): AdminSession
    {
        $session = SessionContext::fromServerRequest($request)
            ->getSession();

        return new DefaultAdminSession($session);
    }
}
