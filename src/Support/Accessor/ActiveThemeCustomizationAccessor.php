<?php

namespace Takemo101\CmsTool\Support\Accessor;

use ArrayObject;
use CmsTool\Theme\ActiveTheme;
use CmsTool\Theme\Contract\ThemeCustomizationAccessor;
use Takemo101\CmsTool\Support\Shared\ImmutableArrayObject;

class ActiveThemeCustomizationAccessor
{
    /**
     * constructor
     *
     * @param ThemeCustomizationPreview $preview
     * @param ActiveTheme $activeTheme
     * @param ThemeCustomizationAccessor $accessor
     */
    public function __construct(
        private readonly ThemeCustomizationPreview $preview,
        private readonly ActiveTheme $activeTheme,
        private readonly ThemeCustomizationAccessor $accessor,
    ) {
        //
    }

    /**
     * @return ArrayObject
     */
    public function __invoke(): ArrayObject
    {
        $data = $this->preview->get();

        return ImmutableArrayObject::of(
            // If the preview data is set, return it.
            $data !== false
                ? $data
                : $this->accessor->load($this->activeTheme),
        );
    }
}
