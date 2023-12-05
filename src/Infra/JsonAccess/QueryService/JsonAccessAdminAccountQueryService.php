<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\QueryService;

use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;
use Takemo101\CmsTool\UseCase\Admin\QueryService\AdminAccountData;
use Takemo101\CmsTool\UseCase\Admin\QueryService\AdminAccountQueryService;
use Takemo101\CmsTool\UseCase\Shared\Exception\NotFoundDataException;

readonly class JsonAccessAdminAccountQueryService implements AdminAccountQueryService
{
    /**
     * constructor
     *
     * @param SettingJsonAccessObjectCreator $creator
     * @param JsonAccessAdminAccountDataMapper $mapper
     */
    public function __construct(
        private SettingJsonAccessObjectCreator $creator,
        private JsonAccessAdminAccountDataMapper $mapper,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function getById(string $id): AdminAccountData
    {
        $object = $this->creator->create();

        /** @var array<string,mixed> */
        $data = $object->get(SettingJsonObjectKeys::RootAdminKey, []);

        $root = $this->mapper->toRootAdmin($data);

        if ($root->id !== $id) {
            throw NotFoundDataException::notFoundData($id);
        }

        return $root;
    }
}
