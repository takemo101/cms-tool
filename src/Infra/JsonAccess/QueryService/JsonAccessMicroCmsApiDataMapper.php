<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\QueryService;

use EventSauce\ObjectHydrator\ObjectMapper;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\MicroCmsApiData;

class JsonAccessMicroCmsApiDataMapper
{
    /**
     * constructor
     *
     * @param ObjectMapper $mapper
     */
    public function __construct(
        private ObjectMapper $mapper,
    ) {
        //
    }

    /**
     * @param array<string,mixed> $data
     * @return MicroCmsApiData
     */
    public function toData(array $data): MicroCmsApiData
    {
        return $this->mapper->hydrateObject(
            MicroCmsApiData::class,
            $data,
        );
    }
}
