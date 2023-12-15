<?php

namespace Takemo101\CmsTool\Domain\Theme;

use CmsTool\Theme\ActiveThemeId;

interface ActiveThemeIdRepository
{
    /**
     * Get the saved active theme ID
     * If it is not saved, return null (do not return the default theme ID)
     *
     * @return ActiveThemeId|null
     */
    public function find(): ?ActiveThemeId;

    /**
     * Save the specified theme ID as an active theme
     *
     * @param ActiveThemeId $id
     * @return void
     */
    public function save(ActiveThemeId $id): void;
}
