<?php

namespace Takemo101\CmsTool\Domain\Theme;

use CmsTool\Theme\ActiveThemeId;
use CmsTool\Theme\ThemeId;

class ActivateThemeService
{
    /**
     * constructor
     *
     * @param ActiveThemeIdRepository $repository
     * @param ExistsThemeIdValidator $validator
     */
    public function __construct(
        private ActiveThemeIdRepository $repository,
        private ExistsThemeIdValidator $validator,
    ) {
        //
    }

    /**
     * Activate the specified theme ID
     *
     * @param ThemeId $id
     * @return ActiveThemeId
     * @throws NotFoundThemeIdException
     */
    public function activate(ThemeId $id): ActiveThemeId
    {
        if (!$this->validator->validate($id)) {
            throw new NotFoundThemeIdException($id);
        }

        $activeThemeId = ActiveThemeId::fromThemeId($id);

        $this->repository->save($activeThemeId);

        return $activeThemeId;
    }
}
