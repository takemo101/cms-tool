<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\QueryService;

use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\MicroCmsApiData;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\MicroCmsApiQueryService;

readonly class JsonAccessMicroCmsApiQueryService implements MicroCmsApiQueryService
{
    /**
     * constructor
     *
     * @param SettingJsonAccessObjectCreator $creator
     * @param JsonAccessMicroCmsApiDataMapper $mapper
     */
    public function __construct(
        private SettingJsonAccessObjectCreator $creator,
        private JsonAccessMicroCmsApiDataMapper $mapper,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function get(): MicroCmsApiData
    {
        $object = $this->creator->create();

        /** @var array<string,mixed> */
        $data = $object->get(
            SettingJsonObjectKeys::MicroCmsApiKey,
            []
        );

        return $this->mapper->toData($data);
    }
}
