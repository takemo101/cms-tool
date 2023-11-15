<?php

namespace CmsTool\Session;

use Odan\Session\SessionInterface as Session;
use Takemo101\Chubby\Http\Support\AbstractContext;

final class SessionContext extends AbstractContext
{
    public const ContextKey = '__session__';

    /**
     * constructor
     *
     * @param Session $session
     */
    public function __construct(
        private Session $session,
    ) {
        //
    }

    /**
     * Get Session instance.
     *
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }
}
