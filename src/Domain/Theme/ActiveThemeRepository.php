<?php

namespace Takemo101\CmsTool\Domain\Theme;

use CmsTool\Theme\ActiveTheme;
use CmsTool\Theme\ThemeId;

interface ActiveThemeRepository
{
    /**
     * Get the saved active theme ID
     * If it is not saved, return null (do not return the default theme ID)
     *
     * @return ActiveTheme|null
     */
    public function find(): ?ActiveTheme;

    /**
     * Save the specified theme ID as an active theme
     *
     * @param ThemeId $id
     * @return void
     */
    public function activate(ThemeId $id): void;
}
