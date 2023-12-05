<?php

namespace Takemo101\CmsTool\UseCase\BasicSetting\Handler;

readonly class SaveBasicSettingCommand
{
    /**
     * constructor
     *
     * @param string $siteName
     * @param RootAdminForSaveBasicSettingCommand $root
     */
    public function __construct(
        public string $siteName,
        public RootAdminForSaveBasicSettingCommand $root,
    ) {
        //
    }
}
