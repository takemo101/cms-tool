<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\Admin\RootAdmin;
use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;

readonly class JsonAccessRootAdminRepository implements RootAdminRepository
{
    public const ObjectKey = 'root';

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
     * @return boolean
     */
    public function has(): bool
    {
        $object = $this->creator->create();

        return $object->has(self::ObjectKey);
    }

    /**
     * @return RootAdmin|null
     */
    public function get(): ?RootAdmin
    {
        $object = $this->creator->create();

        /** @var array<string,mixed>|null */
        $data = $object->get(self::ObjectKey);

        return empty($data)
            ? null
            : $this->mapper->toEntity($data);
    }

    /**
     * @param RootAdmin $root
     * @return void
     */
    public function save(RootAdmin $root): void
    {
        $object = $this->creator->create();

        $object->set(self::ObjectKey, $this->mapper->toArray($root));

        $object->save();
    }
}
