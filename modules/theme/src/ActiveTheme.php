<?php

namespace CmsTool\Theme;

class ActiveTheme extends Theme
{
    /**
     * Create a new instance from a theme.
     *
     * @param Theme $theme
     * @return self
     */
    public static function fromTheme(Theme $theme): self
    {
        return new self(
            id: $theme->id,
            directory: $theme->directory,
            meta: $theme->meta,
            active: true,
        );
    }
}
