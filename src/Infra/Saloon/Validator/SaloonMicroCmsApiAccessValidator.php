<?php

namespace Takemo101\CmsTool\Infra\Saloon\Validator;

use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApi;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiAccessValidator;
use Takemo101\CmsTool\Infra\Saloon\HttpClient\MicroCmsApiConnector;
use Takemo101\CmsTool\Infra\Saloon\HttpClient\Ping\MicroCmsPingRequest;
use Takemo101\CmsTool\Infra\Saloon\HttpClient\Ping\MicroCmsPingResponse;

class SaloonMicroCmsApiAccessValidator implements MicroCmsApiAccessValidator
{
    public const SuccessBody = '{}';

    /**
     * Verify whether the Microcms API key and service ID are valid
     *
     * @param MicroCmsApi $entity
     * @return boolean
     */
    public function validate(MicroCmsApi $entity): bool
    {
        $connector = MicroCmsApiConnector::fromEntity($entity);

        /** @var MicroCmsPingResponse */
        $response = $connector->send(new MicroCmsPingRequest());

        return $response->isValid();
    }
}
