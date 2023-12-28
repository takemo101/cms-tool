<?php

namespace Takemo101\CmsTool\Domain\Install;

interface InstallRepository
{
    /**
     * @return boolean
     */
    public function isInstalled(): bool;
}
