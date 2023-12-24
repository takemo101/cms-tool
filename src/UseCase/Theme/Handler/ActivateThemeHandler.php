<?php

namespace Takemo101\CmsTool\UseCase\Theme\Handler;

use CmsTool\Theme\ThemeId;
use Takemo101\CmsTool\Domain\Theme\ActivateThemeService;
use Takemo101\CmsTool\Domain\Theme\ActiveThemeRepository;
use Takemo101\CmsTool\Domain\Theme\NotFoundThemeIdException;
use Takemo101\CmsTool\UseCase\Shared\Exception\InstallSettingException;

class ActivateThemeHandler
{
    /**
     * constructor
     *
     * @param ActiveThemeRepository $repository
     * @param ActivateThemeService $activateThemeService
     */
    public function __construct(
        private ActiveThemeRepository $repository,
        private ActivateThemeService $activateThemeService,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @return ThemeId
     * @throws NotFoundThemeIdException|InstallSettingException
     */
    public function handle(string $id): ThemeId
    {
        $theme = $this->repository->find();

        if (!$theme) {
            throw InstallSettingException::notExistsSetting();
        }

        $themeId = new ThemeId($id);

        if ($theme->id->equals($themeId)) {
            return $themeId;
        }

        $this->activateThemeService->activate($themeId);

        return $themeId;
    }
}
