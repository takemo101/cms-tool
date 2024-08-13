<?php

namespace Takemo101\CmsTool\Support\Accessor;

use ArrayObject;
use CmsTool\Theme\ActiveTheme;
use CmsTool\Theme\Schema\ThemeCustomizationFetcher;
use Takemo101\CmsTool\Support\Shared\ImmutableArrayObject;

class ActiveThemeCustomizationAccessor
{
    /**
     * constructor
     *
     * @param ActiveTheme $activeTheme
     * @param ThemeCustomizationFetcher $fetcher
     */
    public function __construct(
        private readonly ActiveTheme $activeTheme,
        private readonly ThemeCustomizationFetcher $fetcher,
    ) {
        //
    }

    /**
     * @return ArrayObject
     */
    public function __invoke(): ArrayObject
    {
        return ImmutableArrayObject::of($this->fetcher->get($this->activeTheme));
    }
}
