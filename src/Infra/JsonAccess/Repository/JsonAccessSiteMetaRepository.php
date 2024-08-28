<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\SiteMeta\SiteMeta;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMetaRepository;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;

/**
 * @phpstan-import-type SiteMetaData from JsonAccessSiteMetaEntityMapper
 */
readonly class JsonAccessSiteMetaRepository implements SiteMetaRepository
{
    /**
     * constructor
     *
     * @param SettingJsonAccessObjectCreator $creator
     * @param JsonAccessSiteMetaEntityMapper $mapper
     */
    public function __construct(
        private SettingJsonAccessObjectCreator $creator,
        private JsonAccessSiteMetaEntityMapper $mapper,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function exists(): bool
    {
        $object = $this->creator->create();

        return $object->has(SettingJsonObjectKeys::SiteMetaKey);
    }

    /**
     * {@inheritDoc}
     */
    public function find(): ?SiteMeta
    {
        $object = $this->creator->create();

        /** @var SiteMetaData|null */
        $data = $object->get(SettingJsonObjectKeys::SiteMetaKey);

        return empty($data)
            ? null
            : $this->mapper->toEntity($data);
    }

    /**
     * {@inheritDoc}
     */
    public function save(SiteMeta $meta): void
    {
        $object = $this->creator->create();

        $object->set(
            SettingJsonObjectKeys::SiteMetaKey,
            $this->mapper->toArray($meta),
        );

        $object->save();
    }
}
