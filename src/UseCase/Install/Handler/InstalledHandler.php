<?php

namespace Takemo101\CmsTool\UseCase\Install\Handler;

use CmsTool\Theme\DefaultThemeId;
use Takemo101\CmsTool\Domain\Install\InstallationNotPossibleException;
use Takemo101\CmsTool\Domain\Install\InstalledService;
use Takemo101\CmsTool\Domain\Publish\SitePublishService;
use Takemo101\CmsTool\Domain\Theme\ActivateThemeService;

class InstalledHandler
{
    /**
     * constructor
     *
     * @param InstalledService $installedService
     * @param SitePublishService $publishService
     * @param ActivateThemeService $activateThemeService
     * @param DefaultThemeId $defaultThemeId
     */
    public function __construct(
        private InstalledService $installedService,
        private SitePublishService $publishService,
        private ActivateThemeService $activateThemeService,
        private DefaultThemeId $defaultThemeId,
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
        // Activate the default theme
        $this->activateThemeService->activate($this->defaultThemeId);

        // After installation, make the site private
        $this->publishService->unpublished();

        $this->installedService->installed();
    }
}
