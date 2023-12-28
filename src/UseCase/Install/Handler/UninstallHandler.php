<?php

namespace Takemo101\CmsTool\UseCase\Install\Handler;

use Takemo101\CmsTool\Domain\Install\UninstallService;

class UninstallHandler
{
    /**
     * constructor
     *
     * @param UninstallService $uninstallService
     */
    public function __construct(
        private UninstallService $uninstallService,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @return void
     */
    public function handle(): void
    {
        $this->uninstallService->uninstall();
    }
}
