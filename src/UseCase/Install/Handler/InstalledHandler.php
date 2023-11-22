<?php

namespace Takemo101\CmsTool\UseCase\Install\Handler;

use Takemo101\CmsTool\Domain\Install\InstallationNotPossibleException;
use Takemo101\CmsTool\Domain\Install\InstalledService;

class InstalledHandler
{
    /**
     * constructor
     *
     * @param InstalledService $service
     */
    public function __construct(
        private InstalledService $service,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @return void
     * @throws InstallationNotPossibleException
     */
    public function handle(): void
    {
        $this->service->installed();
    }
}
