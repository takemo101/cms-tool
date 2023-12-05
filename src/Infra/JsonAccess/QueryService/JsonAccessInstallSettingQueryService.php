<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\QueryService;

use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\UseCase\Install\QueryService\InstallSettingData;
use Takemo101\CmsTool\UseCase\Install\QueryService\InstallSettingQueryService;

readonly class JsonAccessInstallSettingQueryService implements InstallSettingQueryService
{
    /**
     * constructor
     *
     * @param SettingJsonAccessObjectCreator $creator
     * @param JsonAccessInstallSettingDataMapper $mapper
     */
    public function __construct(
        private SettingJsonAccessObjectCreator $creator,
        private JsonAccessInstallSettingDataMapper $mapper,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function get(): InstallSettingData
    {
        $object = $this->creator->create();

        return $this->mapper->toData($object->all());
    }
}
