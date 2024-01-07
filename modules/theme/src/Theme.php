<?php

namespace CmsTool\Theme;

class Theme
{
    /**
     * constructor
     *
     * @param ThemeId $id
     * @param string $directory
     * @param ThemeMeta $meta
     * @param boolean $active
     */
    public function __construct(
        public readonly ThemeId $id,
        public readonly string $directory,
        public readonly ThemeMeta $meta,
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

    /**
     * Return whether it's a readonly theme
     *
     * @param boolean $active
     * @return void
     */
    public function isReadonly(): bool
    {
        return $this->meta->readonly;
    }
}
