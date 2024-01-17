<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\Tracking\TrackingCode;
use Takemo101\CmsTool\Domain\Tracking\TrackingCodeRepository;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;

readonly class JsonAccessTrackingCodeRepository implements TrackingCodeRepository
{
    /**
     * constructor
     *
     * @param SettingJsonAccessObjectCreator $creator
     * @param JsonAccessTrackingCodeEntityMapper $mapper
     */
    public function __construct(
        private SettingJsonAccessObjectCreator $creator,
        private JsonAccessTrackingCodeEntityMapper $mapper,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function save(TrackingCode $code): void
    {
        $object = $this->creator->create();

        $object->set(
            SettingJsonObjectKeys::TrackingCodeKey,
            $this->mapper->toArray($code),
        );

        $object->save();
    }
}
