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
     * @return void
     */
    public function save(bool $installed): void;
}
