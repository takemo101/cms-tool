<?php

namespace Takemo101\CmsTool\UseCase\MicroCms\Handler;

use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApi;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiAccessException;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiAccessValidator;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiFactory;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiRepository;

readonly class SaveMicroCmsApiHandler
{
    /**
     * constructor
     *
     * @param MicroCmsApiRepository $repository
     * @param MicroCmsApiFactory $factory
     */
    public function __construct(
        private MicroCmsApiRepository $repository,
        private MicroCmsApiFactory $factory,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @param SaveMicroCmsApiCommand $command
     * @return void
     * @throws MicroCmsApiAccessException
     */
    public function handle(SaveMicroCmsApiCommand $command): void
    {
        $api = $this->factory->create(
            key: $command->key,
            serviceId: $command->serviceId,
        );

        $this->repository->save($api);
    }
}
