<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use EventSauce\ObjectHydrator\ObjectMapper;
use Takemo101\CmsTool\Domain\Tracking\TrackingCode;

class JsonAccessTrackingCodeEntityMapper
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
     * @param TrackingCode $code
     * @return array<string,mixed>
     */
    public function toArray(TrackingCode $code): array
    {
        /** @var array<string,mixed> */
        $serialized = $this->mapper->serializeObject($code);

        return $serialized;
    }
}
