<?php

namespace Takemo101\CmsTool\Preset\Shared\Accessor;

use ArrayObject;
use CmsTool\Theme\Theme;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentQueryService;
use Takemo101\CmsTool\UseCase\Shared\QueryService\Pager;

class TaxonomiesAccessor
{
    /**
     * constructor
     *
     * @param MicroCmsContentQueryService $queryService
     * @param Theme $theme
     * @param string $endpoint
     * @param int $limit
     */
    public function __construct(
        private MicroCmsContentQueryService $queryService,
        private Theme $theme,
        private string $endpoint,
        private int $limit = 50,
    ) {
        //
    }

    /**
     * @return ArrayObject[]
     */
    public function __invoke(): array
    {
        return $this->queryService->getList(
            endpoint: $this->endpoint,
            pager: new Pager(limit: $this->limit),
        )->contents;
    }
}
