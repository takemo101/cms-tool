<?php

namespace Takemo101\CmsTool\Infra\Saloon\HttpClient;

use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiRepository;
use Takemo101\CmsTool\Infra\Shared\Exception\InfraException;

class MicroCmsApiConnectorFactory
{
    /**
     * constructor
     *
     * @param MicroCmsApiRepository $repository
     */
    public function __construct(
        private MicroCmsApiRepository $repository,
    ) {
        //
    }

    /**
     * Create a MicroCmsClient instance
     *
     * @return MicroCmsApiConnector
     * @throws InfraException
     */
    public function create(): MicroCmsApiConnector
    {
        $api = $this->repository->find();

        if (!$api) {
            throw new InfraException('MicroCmsApi is not set');
        }

        return MicroCmsApiConnector::fromEntity($api);
    }
}
