<?php

use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApi;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiRepository;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiFactory;
use Takemo101\CmsTool\UseCase\MicroCms\Handler\SaveMicroCmsApiCommand;
use Takemo101\CmsTool\UseCase\MicroCms\Handler\SaveMicroCmsApiHandler;
use Mockery as m;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiAccessValidator;


it(
    'should save a MicroCmsApi',
    function () {
        // Create a mock for the MicroCmsApiRepository
        $repository = m::mock(MicroCmsApiRepository::class);
        $repository->shouldReceive('save')
            ->once();

        // Create a mock for the SaveMicroCmsApiCommand
        $command = new SaveMicroCmsApiCommand(
            key: 'key',
            serviceId: 'serviceId',
        );

        // Create a mock for the MicroCmsApiFactory
        $validator = m::mock(MicroCmsApiAccessValidator::class);
        $validator->shouldReceive('validate')
            ->once()
            ->andReturn(true);

        $exceptedApi = new MicroCmsApi(
            key: $command->key,
            serviceId: $command->serviceId,
        );

        // Create an instance of the SaveMicroCmsApiHandler
        $handler = new SaveMicroCmsApiHandler($repository, new MicroCmsApiFactory($validator));

        // Call the handle method
        $actual = $handler->handle($command);

        // Assert that the MicroCmsApi object is saved correctly
        expect($actual->key)->toBe($exceptedApi->key);
        expect($actual->serviceId)->toBe($exceptedApi->serviceId);
    }
)->group('microcms-usecase', 'usecase');
