<?php

namespace Takemo101\CmsTool\Preset\Shared\Accessor;

use ArrayObject;
use CmsTool\Theme\Theme;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentQueryService;
use Takemo101\CmsTool\UseCase\Shared\QueryService\Pager;

class TaxonomiesAccessor
{
    /** @var integer */
    public const DefaultLimit = 50;

    /**
     * constructor
     *
     * @param MicroCmsContentQueryService $queryService
     * @param Theme $theme
     * @param string $endpoint
     */
    public function __construct(
        private MicroCmsContentQueryService $queryService,
        private Theme $theme,
        private string $endpoint,
    ) {
        //
    }

    /**
     * @param integer $limit
     * @return ArrayObject[]
     */
    public function __invoke(int $limit = self::DefaultLimit): array
    {
        return $this->queryService->getList(
            endpoint: $this->endpoint,
            pager: new Pager(limit: $limit),
        )->contents;
    }
}
