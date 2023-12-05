<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\QueryService;

use EventSauce\ObjectHydrator\ObjectMapper;
use Takemo101\CmsTool\UseCase\Install\QueryService\InstallSettingData;

class JsonAccessInstallSettingDataMapper
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
     * @return InstallSettingData
     */
    public function toData(array $data): InstallSettingData
    {
        return $this->mapper->hydrateObject(
            InstallSettingData::class,
            $data,
        );
    }
}
