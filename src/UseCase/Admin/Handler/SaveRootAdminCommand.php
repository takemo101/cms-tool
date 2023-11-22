<?php

namespace Takemo101\CmsTool\UseCase\Admin\Handler;

readonly class SaveRootAdminCommand
{
    /**
     * constructor
     *
     * @param string $name
     * @param string $password plain password
     */
    public function __construct(
        public string $name,
        public string $password,
    ) {
        //
    }
}
