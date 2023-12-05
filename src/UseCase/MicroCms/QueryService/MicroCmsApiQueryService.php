<?php

namespace Takemo101\CmsTool\UseCase\MicroCms\QueryService;

interface MicroCmsApiQueryService
{
    /**
     * @return MicroCmsApiData
     */
    public function get(): MicroCmsApiData;
}
