<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\QueryService;

use EventSauce\ObjectHydrator\ObjectMapper;
use Takemo101\CmsTool\UseCase\TrackingCode\QueryService\TrackingCodeData;

class JsonAccessTrackingCodeDataMapper
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
     * @return TrackingCodeData
     */
    public function toData(array $data): TrackingCodeData
    {
        return $this->mapper->hydrateObject(
            TrackingCodeData::class,
            $data,
        );
    }
}
