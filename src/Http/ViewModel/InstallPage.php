<?php

namespace Takemo101\CmsTool\Http\ViewModel;

use Takemo101\CmsTool\UseCase\Install\QueryService\InstallSettingData;

class InstallPage extends ViewModel
{
    /**
     * constructor
     *
     * @param InstallSettingData $setting
     */
    public function __construct(
        public InstallSettingData $setting,
    ) {
        //
    }
}
