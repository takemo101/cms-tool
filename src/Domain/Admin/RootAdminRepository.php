<?php

namespace Takemo101\CmsTool\Domain\Admin;

interface RootAdminRepository
{
    /**
     * @return boolean
     */
    public function has(): bool;

    /**
     * @return RootAdmin|null
     */
    public function get(): ?RootAdmin;

    /**
     * @param RootAdmin $root
     * @return void
     */
    public function save(RootAdmin $root): void;
}
