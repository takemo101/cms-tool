<?php

namespace Takemo101\CmsTool\UseCase\Theme\Handler;

use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemeQueryService;
use Takemo101\CmsTool\UseCase\Shared\Exception\NotFoundDataException;
use Takemo101\CmsTool\UseCase\Theme\Support\ThemeCustomizationTemporaryCache;

/**
 * Save the theme customization data temporarily.
 */
class CacheThemeCustomizationHandler
{
    /**
     * constructor
     *
     * @param ThemeQueryService $queryService
     * @param ThemeCustomizationTemporaryCache $cache
     */
    public function __construct(
        private readonly ThemeQueryService $queryService,
        private readonly ThemeCustomizationTemporaryCache $cache,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @param string $id
     * @param array<string,array<string,mixed>> $data
     * @return array{0:Theme,1:array<string,array<string,mixed>>}
     * @throws NotFoundDataException
     */
    public function handle(string $id, array $data): array
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

        $this->cache->put(
            id: $themeId,
            data: $normalizedData,
        );

        return [$theme, $normalizedData];
    }
}
