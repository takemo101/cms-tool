<?php

namespace Takemo101\CmsTool\Domain\Install;

interface InstallRepository
{
    /**
     * @return boolean
     */
    public function isInstalled(): bool;

    /**
     * Save the installation status
     *
     * @param boolean $installed
     * @return void
     */
    public function save(bool $installed): void;
}
