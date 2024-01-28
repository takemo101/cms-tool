<?php

namespace Takemo101\CmsTool\UseCase\Theme\Handler;

use CmsTool\Theme\Contract\ThemeFinder;
use CmsTool\Theme\Contract\ThemeSaver;
use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemeMeta;
use CmsTool\Theme\ThemePathHelper;
use CmsTool\Theme\ThemeQueryService;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use DI\Attribute\Inject;
use Takemo101\CmsTool\UseCase\Shared\Exception\NotFoundDataException;
use Takemo101\CmsTool\UseCase\Shared\Exception\UseCaseException;

class ChangeThemeMetaHandler
{
    /**
     * constructor
     *
     * @param ThemeQueryService $queryService
     * @param ThemeSaver $saver
     */
    public function __construct(
        private ThemeQueryService $queryService,
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

        $changeTheme = $theme->changeMeta(ThemeMeta::fromArray($payload));

        $this->saver->save($changeTheme);

        return $changeTheme;
    }
}
