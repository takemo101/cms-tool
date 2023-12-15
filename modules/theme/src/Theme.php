<?php

namespace CmsTool\Theme;

class Theme
{
    /**
     * constructor
     *
     * @param ThemeId $id
     * @param string $directory
     * @param ThemeSetting $setting
     * @param boolean $active
     */
    public function __construct(
        public readonly ThemeId $id,
        public readonly string $directory,
        public readonly ThemeSetting $setting,
        private bool $active = false,
    ) {
        //
    }

    /**
     * Return whether it's an active theme
     *
     * @return boolean
     */
    public function isActive(): bool
    {
        return $this->active;
    }
}
