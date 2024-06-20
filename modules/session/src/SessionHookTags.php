<?php

namespace CmsTool\Session;

/**
 * This class defines the tags for the session hooks.
 */
final class SessionHookTags
{
    /**
     * This hook occurs when the session is started.
     * You can obtain the ``Odan\Session\SessionInterface & Odan\Session\SessionManagerInterface`` instance.
     */
    public const SessionStarted = 'session.session_started';

    /**
     * This hook occurs when the flash session is started.
     * You can obtain the ``CmsTool\Session\Flash\FlashSessions`` instance.
     */
    public const FlashSessionStarted = 'session.flash_session_started';

    /**
     * This hook occurs when the CSRF guard is started.
     * You can obtain the ``CmsTool\Session\Csrf\CsrfGuard`` instance.
     */
    public const CsrfGuardStarted = 'session.csrf_guard_started';
}
