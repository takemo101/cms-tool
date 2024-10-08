<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\QueryService;

use EventSauce\ObjectHydrator\ObjectMapper;
use Takemo101\CmsTool\UseCase\Admin\QueryService\RootAdminAccountData;

class JsonAccessAdminAccountDataMapper
{
    /**
     * constructor
     *
     * @param ObjectMapper $mapper
     */
    public function __construct(
        private ObjectMapper $mapper,
    ) {
        //
    }

    /**
     * @param array<string,mixed> $data
     * @return RootAdminAccountData
     */
    public function toRootAdmin(array $data): RootAdminAccountData
    {
        return $this->mapper->hydrateObject(
            RootAdminAccountData::class,
            $data,
        );
    }
}
