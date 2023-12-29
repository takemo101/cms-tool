<?php

namespace Takemo101\CmsTool\Infra\Saloon\HttpClient;

use DI\Attribute\Inject;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApi;
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
        #[Inject('config.system.guzzle')]
        private $guzzleOptions = [],
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

        return $this->createFromEntity($api);
    }

    /**
     * Create a MicroCmsClient instance from entity
     *
     * @param MicroCmsApi $api
     * @return MicroCmsApiConnector
     */
    public function createFromEntity(MicroCmsApi $api): MicroCmsApiConnector
    {
        return MicroCmsApiConnector::fromEntity(
            $api,
            $this->guzzleOptions,
        );
    }
}
