<?php

namespace Takemo101\CmsTool\UseCase\MicroCms\Handler;

readonly class SaveMicroCmsApiCommand
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
