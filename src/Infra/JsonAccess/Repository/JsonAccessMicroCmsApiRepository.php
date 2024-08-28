<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApi;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiRepository;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;

/**
 * @phpstan-import-type MicroCmsApiData from JsonAccessMicroCmsApiEntityMapper
 */
readonly class JsonAccessMicroCmsApiRepository implements MicroCmsApiRepository
{
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
     * {@inheritDoc}
     */
    public function exists(): bool
    {
        $object = $this->creator->create();

        return $object->has(SettingJsonObjectKeys::MicroCmsApiKey);
    }

    /**
     * {@inheritDoc}
     */
    public function find(): ?MicroCmsApi
    {
        $object = $this->creator->create();

        /** @var MicroCmsApiData|null */
        $data = $object->get(SettingJsonObjectKeys::MicroCmsApiKey);

        return empty($data)
            ? null
            : $this->mapper->toEntity($data);
    }

    /**
     * {@inheritDoc}
     */
    public function save(MicroCmsApi $api): void
    {
        $object = $this->creator->create();

        $object->set(
            SettingJsonObjectKeys::MicroCmsApiKey,
            $this->mapper->toArray($api),
        );

        $object->save();
    }
}
