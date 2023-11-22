<?php

namespace CmsTool\Session\Flash;

use Odan\Session\SessionInterface as Session;

interface FlashSession
{
    /**
     * Create a new instance from session
     *
     * @param Session $session
     * @return static
     */
    public static function fromSession(Session $session): static;
}
