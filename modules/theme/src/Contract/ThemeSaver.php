<?php

namespace CmsTool\Theme\Contract;

use CmsTool\Theme\Exception\ThemeSaveException;
use CmsTool\Theme\Theme;

interface ThemeSaver
{
    /**
     * Save a theme.
     *
     * @return Theme
     * @throws ThemeSaveException
     */
    public function save(Theme $theme): void;
}
