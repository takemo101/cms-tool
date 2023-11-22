<?php

namespace Takemo101\CmsTool\UseCase\Shared\QueryService;

readonly class RootAdminData
{
    /**
     * constructor
     *
     * @param string $name
     */
    public function __construct(
        public string $name,
    ) {
        //
    }
}
