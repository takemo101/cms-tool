<?php

namespace Takemo101\CmsTool\UseCase\BasicSetting\Handler;

readonly class RootAdminForSaveBasicSettingCommand
{
    /**
     * constructor
     *
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {
        //
    }
}
