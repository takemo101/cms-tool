<?php

namespace Takemo101\CmsTool\Domain\Tracking;

interface TrackingCodeRepository
{
    /**
     * @param TrackingCode $code
     * @return void
     */
    public function save(TrackingCode $code): void;
}
