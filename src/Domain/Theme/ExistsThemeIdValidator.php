<?php

namespace Takemo101\CmsTool\Domain\Theme;

use CmsTool\Theme\Contract\ThemeFinder;
use CmsTool\Theme\ThemeId;

class ExistsThemeIdValidator
{
    /**
     * constructor
     *
     * @param ThemeFinder $finder
     */
    public function __construct(
        private ThemeFinder $finder,
    ) {
        //
    }

    /**
     * Validate the specified theme ID
     *
     * @param ThemeId $id
     * @return bool
     */
    public function validate(ThemeId $id): bool
    {
        return $this->finder->exists($id);
    }
}
