<?php

namespace Takemo101\CmsTool\Domain\Install;

interface Uninstaller
{
    /**
     * Execute uninstallation processing
     *
     * @return void
     */
    public function uninstall(): void;
}
