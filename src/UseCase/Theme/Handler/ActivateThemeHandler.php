<?php

namespace Takemo101\CmsTool\UseCase\Theme\Handler;

use CmsTool\Theme\ActiveThemeId;
use CmsTool\Theme\ThemeId;
use Takemo101\CmsTool\Domain\Theme\ActivateThemeService;
use Takemo101\CmsTool\Domain\Theme\ActiveThemeIdRepository;
use Takemo101\CmsTool\Domain\Theme\NotFoundThemeIdException;
use Takemo101\CmsTool\UseCase\Shared\Exception\InstallSettingException;

class ActivateThemeHandler
{
    /**
     * constructor
     *
     * @param ActiveThemeIdRepository $repository
     * @param ActivateThemeService $activateThemeService
     */
    public function __construct(
        private ActiveThemeIdRepository $repository,
        private ActivateThemeService $activateThemeService,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @return ActiveThemeId
     * @throws NotFoundThemeIdException|InstallSettingException
     */
    public function handle(string $id): ActiveThemeId
    {
        $activeThemeId = $this->repository->find();

        if (!$activeThemeId) {
            throw InstallSettingException::notExistsSetting();
        }

        $themeId = new ThemeId($id);

        if ($activeThemeId->equals($themeId)) {
            return $activeThemeId;
        }

        return $this->activateThemeService->activate($themeId);
    }
}
