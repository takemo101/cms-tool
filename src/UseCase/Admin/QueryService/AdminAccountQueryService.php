<?php

namespace Takemo101\CmsTool\UseCase\Admin\QueryService;

use Takemo101\CmsTool\UseCase\Shared\Exception\NotFoundDataException;

interface AdminAccountQueryService
{
    /**
     * @param string $id
     * @return AdminAccountData
     * @throws NotFoundDataException
     */
    public function getById(string $id): AdminAccountData;
}
