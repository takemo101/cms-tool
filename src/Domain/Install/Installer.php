<?php

namespace Takemo101\CmsTool\Domain\Install;

interface Installer
{
    /**
     * Execute installation processing
     *
     * @return void
     */
    public function install(): void;
}
