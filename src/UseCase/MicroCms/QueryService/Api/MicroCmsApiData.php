<?php

namespace Takemo101\CmsTool\UseCase\MicroCms\QueryService\Api;

use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApi;

readonly class MicroCmsApiData
{
    /**
     * constructor
     *
     * @param string $key
     * @param string $serviceId
     */
    public function __construct(
        public string $key,
        public string $serviceId,
    ) {
        //
    }

    /**
     * @return MicroCmsApi
     */
    public function toEntity(): MicroCmsApi
    {
        return new MicroCmsApi(
            $this->key,
            $this->serviceId,
        );
    }
}
