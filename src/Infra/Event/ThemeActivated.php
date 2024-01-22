<?php

namespace Takemo101\CmsTool\Infra\Event;

use CmsTool\Theme\ThemeId;
use Takemo101\Chubby\Event\StoppableEvent;

class ThemeActivated extends StoppableEvent
{
    /**
     * constructor
     *
     * @param ThemeId $activeThemeId
     */
    public function __construct(
        private ThemeId $activeThemeId,
    ) {
        //
    }

    /**
     * Theme before change
     *
     * @return ThemeId
     */
    public function getActiveThemeId(): ThemeId
    {
        return $this->activeThemeId;
    }
}
