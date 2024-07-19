<?php

namespace Takemo101\CmsTool\Http\ViewModel;

use CmsTool\Theme\ActiveTheme;
use CmsTool\Theme\Theme;
use LogicException;

class ThemeIndexPage extends ViewModel
{
    /**
     * constructor
     *
     * @param (Theme|ActiveTheme)[] $themes
     */
    public function __construct(
        private readonly array $themes,
    ) {
        //
    }

    /**
     * @return Theme
     */
    public function activeTheme(): Theme
    {
        foreach ($this->themes as $theme) {
            if ($theme->isActive()) {
                return $theme;
            }
        }

        throw new LogicException('Active theme not found');
    }

    /**
     * @return Theme[]
     */
    public function themes(): array
    {
        return array_filter(
            $this->themes,
            fn (Theme $theme) => $theme->isActive() === false,
        );
    }
}
