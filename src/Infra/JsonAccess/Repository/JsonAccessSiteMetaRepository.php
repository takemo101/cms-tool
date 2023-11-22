<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\Admin\RootAdmin;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMeta;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMetaRepository;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;

readonly class JsonAccessSiteMetaRepository implements SiteMetaRepository
{
    public const ObjectKey = 'meta';

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
     * @return boolean
     */
    public function has(): bool
    {
        $object = $this->creator->create();

        return $object->has(self::ObjectKey);
    }

    /**
     * @param SiteMeta $meta
     * @return void
     */
    public function save(SiteMeta $meta): void
    {
        $object = $this->creator->create();

        $object->set(self::ObjectKey, $this->mapper->toArray($meta));

        $object->save();
    }
}
