<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Contract\ActiveThemeIdMatcher;

class DefaultActiveThemeIdMatcher implements ActiveThemeIdMatcher
{
    /**
     * constructor
     *
     * @param DefaultThemeId $id
     */
    public function __construct(
        private readonly DefaultThemeId $id,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function isMatch(ThemeId $id): bool
    {
        return $this->id->equals($id);
    }
}
