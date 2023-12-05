<?php

namespace Takemo101\CmsTool\UseCase\Admin\Handler\Login;

readonly class LoginAdminCommand
{
    /**
     * constructor
     *
     * @param string $email
     * @param string $password plain password
     */
    public function __construct(
        public string $email,
        public string $password,
    ) {
        //
    }
}
