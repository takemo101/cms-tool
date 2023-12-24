<?php

namespace Takemo101\CmsTool\Domain\Theme;

use CmsTool\Theme\ThemeId;

class ActivateThemeService
{
    /**
     * constructor
     *
     * @param ActiveThemeRepository $repository
     * @param ExistsThemeIdValidator $validator
     */
    public function __construct(
        private ActiveThemeRepository $repository,
        private ExistsThemeIdValidator $validator,
    ) {
        //
    }

    /**
     * Activate the specified theme ID
     *
     * @param ThemeId $id
     * @return void
     * @throws NotFoundThemeIdException
     */
    public function activate(ThemeId $id): void
    {
        if (!$this->validator->validate($id)) {
            throw new NotFoundThemeIdException($id);
        }

        $this->repository->activate($id);
    }
}
