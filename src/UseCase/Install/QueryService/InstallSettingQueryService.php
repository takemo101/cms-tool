<?php

namespace Takemo101\CmsTool\UseCase\Install\QueryService;

interface InstallSettingQueryService
{
    /**
     * @return InstallSettingData
     */
    public function get(): InstallSettingData;
}
