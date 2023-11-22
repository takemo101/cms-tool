<?php

namespace Takemo101\CmsTool\Domain\Admin;

interface RootAdminRepository
{
    /**
     * @return boolean
     */
    public function exists(): bool;

    /**
     * @return RootAdmin|null
     */
    public function find(): ?RootAdmin;

    /**
     * @param RootAdmin $root
     * @return void
     */
    public function save(RootAdmin $root): void;
}
