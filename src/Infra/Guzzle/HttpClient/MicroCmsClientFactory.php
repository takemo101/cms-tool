<?php

namespace Takemo101\CmsTool\Infra\Guzzle\HttpClient;

use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiRepository;
use Exception;

readonly class MicroCmsClientFactory
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
     * @return MicroCmsClient
     * @throws Exception
     */
    public function create(): MicroCmsClient
    {
        $api = $this->repository->get();

        if (!$api) {
            throw new Exception('MicroCmsApi is not set');
        }

        return MicroCmsClient::fromEntity($api);
    }
}
