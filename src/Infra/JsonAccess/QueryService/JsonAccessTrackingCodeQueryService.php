<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\QueryService;

use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;
use Takemo101\CmsTool\UseCase\TrackingCode\QueryService\TrackingCodeData;
use Takemo101\CmsTool\UseCase\TrackingCode\QueryService\TrackingCodeQueryService;

readonly class JsonAccessTrackingCodeQueryService implements TrackingCodeQueryService
{
    /**
     * constructor
     *
     * @param SettingJsonAccessObjectCreator $creator
     * @param JsonAccessTrackingCodeDataMapper $mapper
     */
    public function __construct(
        private SettingJsonAccessObjectCreator $creator,
        private JsonAccessTrackingCodeDataMapper $mapper,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function get(): TrackingCodeData
    {
        $object = $this->creator->create();

        /** @var array<string,mixed> */
        $data = $object->get(
            SettingJsonObjectKeys::TrackingCodeKey,
            []
        );

        return $this->mapper->toData($data);
    }
}
