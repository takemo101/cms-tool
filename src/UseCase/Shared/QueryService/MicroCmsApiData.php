<?php

namespace Takemo101\CmsTool\UseCase\Shared\QueryService;

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
}
