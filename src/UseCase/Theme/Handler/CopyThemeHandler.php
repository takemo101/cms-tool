<?php

namespace Takemo101\CmsTool\UseCase\Theme\Handler;

use CmsTool\Theme\Contract\ThemeFinder;
use CmsTool\Theme\Contract\ThemeSaver;
use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemePathHelper;
use CmsTool\Theme\ThemeQueryService;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use DI\Attribute\Inject;
use Takemo101\CmsTool\UseCase\Shared\Exception\NotFoundDataException;
use Takemo101\CmsTool\UseCase\Shared\Exception\UseCaseException;

class CopyThemeHandler
{
    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     * @param ThemeFinder $finder
     * @param ThemeQueryService $queryService
     * @param ThemePathHelper $helper
     * @param ThemeSaver $saver
     * @param string $copyLocation
     */
    public function __construct(
        private LocalFilesystem $filesystem,
        private ThemeFinder $finder,
        private ThemeQueryService $queryService,
        private ThemePathHelper $helper,
        private ThemeSaver $saver,
        #[Inject('config.theme.copy')]
        private string $copyLocation = '',
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

        $copyThemeId = $this->generateCopyThemeId();

        $copyTheme = $theme->copy(
            $copyThemeId,
            $this->helper->getThemeLocation(
                $this->copyLocation,
                $copyThemeId,
            ),
        );

        $fromThemePath = $this->helper->getThemePath($theme);

        $toThemePath = $this->helper->getThemePath($copyTheme);

        $this->filesystem->makeDirectory($toThemePath);

        if (!$this->filesystem->copyDirectory(
            $fromThemePath,
            $toThemePath,
        )) {
            throw new UseCaseException(
                message: 'Failed to copy theme.',
            );
        }

        $this->saver->save($copyTheme);

        $this->copyCustomization($theme, $copyTheme);

        return $copyTheme;
    }

    /**
     * Generate a unique theme id for the copy.
     *
     * @return ThemeId
     */
    private function generateCopyThemeId(): ThemeId
    {
        do {
            $copyThemeId = ThemeId::generate();
        } while ($this->finder->exists($copyThemeId));

        return $copyThemeId;
    }

    /**
     * Copy the customization data of the specified theme.
     *
     * @param Theme $fromTheme
     * @param Theme $toTheme
     * @return void
     */
    private function copyCustomization(
        Theme $fromTheme,
        Theme $toTheme,
    ): void {
        $toThemeDataPath = $this->helper->getCustomizationDataPath($toTheme);

        if ($this->filesystem->exists($toThemeDataPath)) {
            return;
        }

        $fromThemeDataPath = $this->helper->getCustomizationDataPath($fromTheme);

        if (!$this->filesystem->copy($fromThemeDataPath, $toThemeDataPath)) {
            throw new UseCaseException(
                message: 'Failed to copy theme customization data.',
            );
        }
    }
}
