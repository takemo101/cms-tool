<?php

namespace Takemo101\CmsTool\Support\Accessor;

use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Api\MicroCmsApiData;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Api\MicroCmsApiQueryService;

class MicroCmsApiAccessor
{
    /**
     * constructor
     *
     * @param MicroCmsApiQueryService $queryService
     */
    public function __construct(
        private MicroCmsApiQueryService $queryService,
    ) {
        //
    }

    /**
     * @return MicroCmsApiData
     */
    public function __invoke(): MicroCmsApiData
    {
        return $this->queryService->get();
    }
}
