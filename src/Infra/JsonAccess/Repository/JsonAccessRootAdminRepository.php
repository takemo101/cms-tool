<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\Admin\RootAdmin;
use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;

/**
 * @phpstan-import-type RootAdminData from JsonAccessRootAdminEntityMapper
 */
readonly class JsonAccessRootAdminRepository implements RootAdminRepository
{
    /**
     * constructor
     *
     * @param SettingJsonAccessObjectCreator $creator
     * @param JsonAccessRootAdminEntityMapper $mapper
     */
    public function __construct(
        private SettingJsonAccessObjectCreator $creator,
        private JsonAccessRootAdminEntityMapper $mapper,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function exists(): bool
    {
        $object = $this->creator->create();

        return $object->has(SettingJsonObjectKeys::RootAdminKey);
    }

    /**
     * {@inheritDoc}
     */
    public function find(): ?RootAdmin
    {
        $object = $this->creator->create();

        /** @var RootAdminData|null */
        $data = $object->get(SettingJsonObjectKeys::RootAdminKey);

        return empty($data)
            ? null
            : $this->mapper->toEntity($data);
    }

    /**
     * {@inheritDoc}
     */
    public function save(RootAdmin $root): void
    {
        $object = $this->creator->create();

        $object->set(
            SettingJsonObjectKeys::RootAdminKey,
            $this->mapper->toArray($root),
        );

        $object->save();
    }
}
