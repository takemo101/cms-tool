<?php

namespace Takemo101\CmsTool\UseCase\TrackingCode\QueryService;

interface TrackingCodeQueryService
{
    /**
     * @return TrackingCodeData
     */
    public function get(): TrackingCodeData;
}
