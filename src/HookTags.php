<?php

namespace Takemo101\CmsTool;

class HookTags
{
    /**
     * A hook tag called when registering an active theme route.
     * The Hook method takes the `` RouteCollectorProxyInterface`` as an argument.
     */
    public const ActiveThemeRouteRegistered = 'cmstool.active_theme_route_registered';

    /**
     * Hook tag called when loading the active theme
     * The Hook method takes the ``ActiveTheme`` as an argument.
     */
    public const ActiveThemeLoaded = 'cmstool.active_theme_loaded';

    /**
     * Hook tag called when the administrator session is started.
     * The Hook method takes the ``AdminSession`` as an argument.
     */
    public const AdminSessionStarted = 'cmstool.admin_session_started';
};
