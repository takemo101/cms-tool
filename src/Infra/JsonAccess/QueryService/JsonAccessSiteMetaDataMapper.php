<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\QueryService;

use EventSauce\ObjectHydrator\ObjectMapper;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaData;

class JsonAccessSiteMetaDataMapper
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
     * @return SiteMetaData
     */
    public function toData(array $data): SiteMetaData
    {
        return $this->mapper->hydrateObject(
            SiteMetaData::class,
            $data,
        );
    }
}
