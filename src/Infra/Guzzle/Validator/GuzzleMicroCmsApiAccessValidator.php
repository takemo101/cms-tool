<?php

namespace Takemo101\CmsTool\Infra\Guzzle\Validator;

use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApi;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiAccessValidator;
use Takemo101\CmsTool\Infra\Guzzle\HttpClient\MicroCmsClient;

class GuzzleMicroCmsApiAccessValidator implements MicroCmsApiAccessValidator
{
    /**
     * Verify whether the Microcms API key and service ID are valid
     *
     * @param string $key
     * @param string $serviceId
     * @return boolean
     */
    public function validate(MicroCmsApi $entity): bool
    {
        $client = MicroCmsClient::fromEntity($entity);

        return $client->ping();
    }
}
