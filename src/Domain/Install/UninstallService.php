<?php

namespace Takemo101\CmsTool\Domain\Install;

class UninstallService
{
    /**
     * constructor
     *
     * @param InstallRepository $repository
     * @param Uninstaller $uninstaller
     */
    public function __construct(
        private InstallRepository $repository,
        private Uninstaller $uninstaller,
    ) {
        //
    }

    /**
     * @return void
     */
    public function uninstall(): void
    {
        if ($this->repository->isInstalled()) {
            $this->uninstaller->uninstall();
        }
    }
}
