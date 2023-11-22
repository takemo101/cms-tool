<?php

namespace Takemo101\CmsTool\Http\ViewModel;

use Takemo101\CmsTool\UseCase\Shared\QueryService\InstallSettingData;

readonly class InstallPage extends ViewModel
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
