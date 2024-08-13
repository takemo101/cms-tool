<?php

namespace Takemo101\CmsTool\UseCase\Theme\Handler;

use CmsTool\Theme\Contract\ThemeSaver;
use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemeMetaFactory;
use CmsTool\Theme\ThemeQueryService;
use Takemo101\CmsTool\UseCase\Shared\Exception\NotFoundDataException;

class ChangeThemeMetaHandler
{
    /**
     * constructor
     *
     * @param ThemeQueryService $queryService
     * @param ThemeMetaFactory $factory
     * @param ThemeSaver $saver
     */
    public function __construct(
        private ThemeQueryService $queryService,
        private ThemeMetaFactory $factory,
        private ThemeSaver $saver,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @param string $id
     * @param array $payload
     * @return Theme
     * @throws NotFoundDataException
     * @throws ArrayKeyMissingException
     */
    public function handle(string $id, array $payload): Theme
    {
        $themeId = new ThemeId($id);

        try {
            $theme = $this->queryService->getOne($themeId);
        } catch (NotFoundThemeException $e) {
            throw new NotFoundDataException(
                message: $e->getMessage(),
                previous: $e,
            );
        }

        $changeTheme = $theme->changeMeta(
            $this->factory->create($payload),
        );

        $this->saver->save($changeTheme);

        return $changeTheme;
    }
}
