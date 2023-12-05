<?php

namespace Takemo101\CmsTool\UseCase\Admin\QueryService;

use Takemo101\CmsTool\Domain\Admin\AdminId;

readonly class RootAdminAccountData extends AdminAccountData
{
    /**
     * constructor
     *
     * @param string $name
     * @param string $email
     */
    public function __construct(
        string $name,
        string $email,
    ) {
        parent::__construct(
            id: AdminId::RootIdSymbol,
            name: $name,
            email: $email,
        );
    }
}
