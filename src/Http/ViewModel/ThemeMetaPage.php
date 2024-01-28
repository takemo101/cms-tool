<?php

namespace Takemo101\CmsTool\Http\ViewModel;

use CmsTool\Theme\Support\ThemeMetaReader;
use CmsTool\Theme\Theme;

class ThemeMetaPage extends ViewModel
{
    /**
     * constructor
     *
     * @param Theme $theme
     */
    public function __construct(
        public Theme $theme,
    ) {
        //
    }

    /**
     * @param ThemeMetaReader $reader
     * @return string
     */
    public function meta(
        ThemeMetaReader $reader,
    ): string {
        return $reader->get($this->theme);
    }
}
