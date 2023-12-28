<?php

namespace Takemo101\CmsTool\Http\Session;

use CmsTool\Session\SessionContext;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\CmsTool\UseCase\Admin\Auth\AdminSession;
use RuntimeException;

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
        $session = SessionContext::fromRequest($request)
            ?->getSession();

        if (!$session) {
            throw new RuntimeException('Session not found.');
        }

        return new DefaultAdminSession($session);
    }
}
