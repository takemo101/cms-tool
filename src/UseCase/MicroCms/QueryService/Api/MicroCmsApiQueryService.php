<?php

namespace Takemo101\CmsTool\UseCase\MicroCms\QueryService\Api;

interface MicroCmsApiQueryService
{
    /**
     * @return MicroCmsApiData
     */
    public function get(): MicroCmsApiData;
}
