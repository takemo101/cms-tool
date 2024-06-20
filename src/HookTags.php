<?php

namespace Takemo101\CmsTool;

class HookTags
{
    /**
     * A hook tag called when registering an active theme route.
     * The Hook method takes the `` RoutecollectorProxyInterface`` as an argument.
     */
    public const Theme_RegisterThemeRoute = 'theme.register_theme_route';

    /**
     * Hook tag called when loading the active theme
     * The Hook method takes the ``ActiveTheme`` as an argument.
     */
    public const Theme_LoadedActiveTheme = 'theme.loaded_active_theme';

    /**
     * Hook tag called when the administrator session is started.
     * The Hook method takes the ``AdminSession`` as an argument.
     */
    public const AdminSessionStarted = 'admin_session_started';
};
