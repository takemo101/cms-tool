<?php

namespace Takemo101\CmsTool\Http\ViewModel;

use ArrayObject;
use CmsTool\Theme\Contract\ThemeCustomizationLoader;
use CmsTool\Theme\Theme;
use Takemo101\CmsTool\Support\Shared\ImmutableArrayObject;

class ThemeCustomizationEditPage extends ViewModel
{
    /**
     * constructor
     *
     * @param Theme $theme
     */
    public function __construct(
        public readonly Theme $theme,
    ) {
        //
    }

    /**
     * Get theme customization data.
     *
     * @param ThemeCustomizationLoader $loader
     * @return ArrayObject
     */
    public function data(
        ThemeCustomizationLoader $loader,
    ): ArrayObject {
        return ImmutableArrayObject::of(
            $loader->load($this->theme),
        );
    }
}
