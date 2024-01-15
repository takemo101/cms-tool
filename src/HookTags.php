<?php

namespace Takemo101\CmsTool;

class HookTags
{
    /**
     * A hook tag called when registering an active theme route.
     * The Hook method takes the `` RoutecollectorProxyInterface`` as an argument.
     */
    public const RegisterThemeRoute = 'theme.routing.register';

    /**
     * A hook tag called when creating a template view of the theme.
     * The Hook method takes the ``View`` as an argument.
     */
    public const CreateThemeTemplate = 'theme.template.create';

    /**
     * Hook tag called when loading the active theme
     * The Hook method takes the ``ActiveTheme`` as an argument.
     */
    public const LoadActiveTheme = 'theme.active.load';
};
