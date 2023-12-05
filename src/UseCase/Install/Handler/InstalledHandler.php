<?php

namespace Takemo101\CmsTool\UseCase\Install\Handler;

use Takemo101\CmsTool\Domain\Install\InstallationNotPossibleException;
use Takemo101\CmsTool\Domain\Install\InstalledService;
use Takemo101\CmsTool\Domain\Publish\SitePublishService;

class InstalledHandler
{
    /**
     * constructor
     *
     * @param InstalledService $installedService
     * @param SitePublishService $publishService
     */
    public function __construct(
        private InstalledService $installedService,
        private SitePublishService $publishService,
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
        $this->installedService->installed();

        // After installation, make the site private
        $this->publishService->unpublished();
    }
}
