<?php

namespace Takemo101\CmsTool\UseCase\Admin\Handler\Update;

readonly class ChangeAdminAccountCommand
{
    /**
     * constructor
     *
     * @param string $name
     * @param string $email
     * @param string $password plain password
     */
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password,
    ) {
        //
    }
}
