<?php

namespace Takemo101\CmsTool\Domain\MicroCms;

interface MicroCmsApiAccessValidator
{
    /**
     * Verify whether the Microcms API key and service ID are valid
     *
     * @param string $key
     * @param string $serviceId
     * @return boolean
     */
    public function validate(MicroCmsApi $entity): bool;
}
