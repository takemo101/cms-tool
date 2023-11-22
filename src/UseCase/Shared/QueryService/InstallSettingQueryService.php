<?php

namespace Takemo101\CmsTool\UseCase\Shared\QueryService;

use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;

interface InstallSettingQueryService
{
    /**
     * @return InstallSettingData
     */
    public function get(): InstallSettingData;
}
