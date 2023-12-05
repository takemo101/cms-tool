<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\Publish\SitePublishRepository;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;

class JsonAccessSitePublishRepository implements SitePublishRepository
{
    /**
     * constructor
     *
     * @param SettingJsonAccessObjectCreator $creator
     */
    public function __construct(
        private SettingJsonAccessObjectCreator $creator,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function isPublished(): bool
    {
        $object = $this->creator->create();

        if (!$object->has(SettingJsonObjectKeys::PublishedKey)) {
            return false;
        }

        /** @var boolean */
        $published = $object->get(SettingJsonObjectKeys::PublishedKey);

        return (bool) $published;
    }

    /**
     * {@inheritDoc}
     */
    public function save(bool $published): void
    {
        $object = $this->creator->create();

        $object->set(SettingJsonObjectKeys::PublishedKey, $published);

        $object->save();
    }
}
