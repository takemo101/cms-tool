<?php

namespace Takemo101\CmsTool\Domain\MicroCms;

use InvalidArgumentException;

readonly class MicroCmsApi
{
    /**
     * constructor
     *
     * @param string $key
     * @param string $serviceId
     * @throws InvalidArgumentException
     */
    public function __construct(
        public string $key,
        public string $serviceId,
    ) {
        assert(!empty($key), 'key is empty');
        assert(!empty($serviceId), 'serviceId is empty');
    }
}
