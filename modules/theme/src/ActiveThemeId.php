<?php

namespace CmsTool\Theme;

class ActiveThemeId extends ThemeId
{
    /**
     * Change the id of the active theme.
     *
     * @param ThemeId $id
     * @return void
     */
    public function change(ThemeId $id): void
    {
        $this->set($id->value());
    }
}
