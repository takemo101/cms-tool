<?php

namespace Takemo101\CmsTool\Http\Session;

use Psr\Http\Message\ServerRequestInterface;
use Takemo101\CmsTool\UseCase\Admin\Auth\AdminSession;

interface AdminSessionFactory
{
    /**
     * Create a session object
     *
     * @param ServerRequestInterface $request
     * @return AdminSession
     */
    public function create(ServerRequestInterface $request): AdminSession;
}
