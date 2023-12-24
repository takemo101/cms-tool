<?php

namespace Takemo101\CmsTool\HookTags {
    /**
     * A hook tag called when registering an active theme route.
     * The Hook method takes the `` RoutecollectorProxyInterface`` as an argument.
     */
    const RegisterThemeRoute = 'theme.routing.register';

    /**
     * A hook tag called when creating a template view of the theme.
     * The Hook method takes the ``View`` as an argument.
     */
    const CreateThemeTemplate = 'theme.template.create';

    /**
     * Hook tag called when loading the active theme
     * The Hook method takes the ``ActiveTheme`` as an argument.
     */
    const LoadActiveTheme = 'theme.active.load';
};
