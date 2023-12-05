<?php

namespace Takemo101\CmsTool\UseCase\Admin\QueryService;

readonly class AdminAccountData
{
    /**
     * constructor
     *
     * @param string $id
     * @param string $name
     * @param string $email
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
    ) {
        //
    }
}
