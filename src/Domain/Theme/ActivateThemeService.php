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
     * @return void
     * @throws ThemeIdException
     */
    public function activate(ThemeId $id): void
    {
        if (!$this->validator->validate($id)) {
            throw ThemeIdException::notExists($id);
        }

        $this->repository->save(ActiveThemeId::fromThemeId($id));
    }
}
