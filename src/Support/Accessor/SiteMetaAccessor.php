<?php

namespace Takemo101\CmsTool\Support\Accessor;

use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaData;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaQueryService;

class SiteMetaAccessor
{
    /**
     * constructor
     *
     * @param SiteMetaQueryService $queryService
     */
    public function __construct(
        private SiteMetaQueryService $queryService,
    ) {
        //
    }

    /**
     * @return SiteMetaData
     */
    public function __invoke(): SiteMetaData
    {
        return $this->queryService->get();
    }
}
