<?php

namespace CmsTool\Session\Contract;

use Odan\Session\SessionInterface as Session;
use Odan\Session\SessionManagerInterface as SessionManager;

interface SessionFactory
{
    /**
     * Create session.
     *
     * @return Session & SessionManager
     */
    public function create(): Session & SessionManager;
}
