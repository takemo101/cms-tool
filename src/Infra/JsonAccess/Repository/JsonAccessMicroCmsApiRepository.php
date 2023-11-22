<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApi;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiRepository;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;

readonly class JsonAccessMicroCmsApiRepository implements MicroCmsApiRepository
{
    public const ObjectKey = 'api';

    /**
     * constructor
     *
     * @param SettingJsonAccessObjectCreator $creator
     * @param JsonAccessMicroCmsApiEntityMapper $mapper
     */
    public function __construct(
        private SettingJsonAccessObjectCreator $creator,
        private JsonAccessMicroCmsApiEntityMapper $mapper,
    ) {
        //
    }

    /**
     * @return boolean
     */
    public function exists(): bool
    {
        $object = $this->creator->create();

        return $object->has(self::ObjectKey);
    }

    /**
     * @return MicroCmsApi|null
     */
    public function find(): ?MicroCmsApi
    {
        $object = $this->creator->create();

        /** @var array<string,mixed>|null */
        $data = $object->get(self::ObjectKey);

        return empty($data)
            ? null
            : $this->mapper->toEntity($data);
    }

    /**
     * @param MicroCmsApi $api
     * @return void
     */
    public function save(MicroCmsApi $api): void
    {
        $object = $this->creator->create();

        $object->set(self::ObjectKey, $this->mapper->toArray($api));

        $object->save();
    }
}
