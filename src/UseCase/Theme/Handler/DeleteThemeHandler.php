<?php

namespace Takemo101\CmsTool\UseCase\Theme\Handler;

use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemePathHelper;
use CmsTool\Theme\ThemeQueryService;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\CmsTool\UseCase\Shared\Exception\NotFoundDataException;
use Takemo101\CmsTool\UseCase\Shared\Exception\UseCaseException;

class DeleteThemeHandler
{
    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     * @param ThemeQueryService $queryService
     * @param ThemePathHelper $helper
     */
    public function __construct(
        private LocalFilesystem $filesystem,
        private ThemeQueryService $queryService,
        private ThemePathHelper $helper,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @param string $id
     * @return Theme
     * @throws NotFoundDataException
     */
    public function handle(string $id): Theme
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

        $path = $theme->delete();

        if (!$this->filesystem->deleteDirectory($path)) {
            throw new UseCaseException(
                message: 'Failed to delete theme directory.',
            );
        }

        return $theme;
    }
}
