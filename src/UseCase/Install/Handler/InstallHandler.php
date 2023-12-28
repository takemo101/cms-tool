<?php

namespace Takemo101\CmsTool\UseCase\Install\Handler;

use CmsTool\Theme\DefaultThemeId;
use Takemo101\CmsTool\Domain\Install\InstallationNotPossibleException;
use Takemo101\CmsTool\Domain\Install\InstallService;
use Takemo101\CmsTool\Domain\Publish\SitePublishService;
use Takemo101\CmsTool\Domain\Theme\ActivateThemeService;
use Takemo101\CmsTool\Domain\Webhook\WebhookToken;
use Takemo101\CmsTool\Domain\Webhook\WebhookTokenRepository;

class InstallHandler
{
    /**
     * constructor
     *
     * @param InstallService $installService
     * @param SitePublishService $publishService
     * @param ActivateThemeService $activateThemeService
     * @param DefaultThemeId $defaultThemeId
     * @param WebhookTokenRepository $tokenRepository
     */
    public function __construct(
        private InstallService $installService,
        private SitePublishService $publishService,
        private ActivateThemeService $activateThemeService,
        private DefaultThemeId $defaultThemeId,
        private WebhookTokenRepository $tokenRepository,
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
        // Generate a new webhook token
        $this->tokenRepository->save(
            WebhookToken::generate(),
        );

        // Activate the default theme
        $this->activateThemeService->activate($this->defaultThemeId);

        // After installation, make the site private
        $this->publishService->unpublish();

        $this->installService->install();
    }
}
