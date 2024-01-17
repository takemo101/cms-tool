<?php

namespace Takemo101\CmsTool\Domain\Tracking;

interface TrackingCodeRepository
{
    /**
     * @return boolean
     */
    public function exists(): bool;

    /**
     * @return TrackingCode|null
     */
    public function find(): ?TrackingCode;

    /**
     * @param TrackingCode $code
     * @return void
     */
    public function save(TrackingCode $code): void;
}
