<?php

namespace Takemo101\CmsTool\Support\Accessor;

use CmsTool\Theme\ActiveTheme;
use CmsTool\Theme\Schema\ThemeCustomizationFetcher;
use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObject;
use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObjectable;

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
     * @return ImmutableArrayObjectable<string,array<string,mixed>>
     */
    public function __invoke(): ImmutableArrayObjectable
    {
        return ImmutableArrayObject::of($this->fetcher->get($this->activeTheme));
    }
}
