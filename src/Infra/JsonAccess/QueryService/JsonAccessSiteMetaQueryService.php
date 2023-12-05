<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\QueryService;

use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaData;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaQueryService;

readonly class JsonAccessSiteMetaQueryService implements SiteMetaQueryService
{
    /**
     * constructor
     *
     * @param SettingJsonAccessObjectCreator $creator
     * @param JsonAccessSiteMetaDataMapper $mapper
     */
    public function __construct(
        private SettingJsonAccessObjectCreator $creator,
        private JsonAccessSiteMetaDataMapper $mapper,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function get(): SiteMetaData
    {
        $object = $this->creator->create();

        /** @var array<string,mixed> */
        $data = $object->get(
            SettingJsonObjectKeys::SiteMetaKey,
            []
        );

        return $this->mapper->toData($data);
    }
}
