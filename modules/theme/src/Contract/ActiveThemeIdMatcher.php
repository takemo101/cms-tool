<?php

namespace CmsTool\Theme\Contract;

use CmsTool\Theme\ThemeId;

interface ActiveThemeIdMatcher
{
    /**
     * Judge whether it is an active theme ID
     *
     * @param ThemeId $id
     * @return bool
     */
    public function isMatch(ThemeId $id): bool;
}
