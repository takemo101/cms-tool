<?php

namespace Takemo101\CmsTool\UseCase\Theme\Handler;

use CmsTool\Theme\Contract\ThemeCustomizationSaver;
use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemeQueryService;
use Takemo101\CmsTool\UseCase\Shared\Exception\NotFoundDataException;
use Takemo101\CmsTool\UseCase\Theme\Support\ThemeCustomizationTemporaryCache;

/**
 * Apply the completed customization data to the theme file.
 */
class ApplyThemeCustomizationHandler
{
    /**
     * constructor
     *
     * @param ThemeQueryService $queryService
     * @param ThemeCustomizationSaver $saver
     * @param ThemeCustomizationTemporaryCache $cache
     */
    public function __construct(
        private readonly ThemeQueryService $queryService,
        private readonly ThemeCustomizationSaver $saver,
        private readonly ThemeCustomizationTemporaryCache $cache,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @param string $id
     * @param array<string,array<string,mixed>> $data
     * @return Theme
     * @throws NotFoundDataException
     */
    public function handle(string $id, array $data): Theme
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

        $normalizedData = $theme->normalizeCustomization($data);

        $this->saver->save(
            theme: $theme,
            data: $normalizedData,
        );

        $this->cache->clear($themeId);

        return $theme;
    }
}
