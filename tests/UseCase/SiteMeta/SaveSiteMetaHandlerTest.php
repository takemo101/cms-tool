<?php

use Takemo101\CmsTool\Domain\SiteMeta\SiteMeta;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMetaRepository;
use Takemo101\CmsTool\UseCase\SiteMeta\Handler\SaveSiteMetaCommand;
use Takemo101\CmsTool\UseCase\SiteMeta\Handler\SaveSiteMetaHandler;
use Mockery as m;

it(
    'should save a SiteMeta',
    function () {
        // Create a mock for the SiteMetaRepository
        $repository = m::mock(SiteMetaRepository::class);
        $repository->shouldReceive('save')
            ->once();

        // Create a mock for the SaveSiteMetaCommand
        $command = new SaveSiteMetaCommand(
            name: 'name',
            title: 'title',
            description: 'description',
        );

        // Create an instance of the SaveSiteMetaHandler
        $handler = new SaveSiteMetaHandler($repository);

        // Call the handle method
        $actual = $handler->handle($command);

        // Assert that the SiteMeta object is saved correctly
        expect($actual->name)->toBe($command->name);
        expect($actual->title)->toBe($command->title);
        expect($actual->description)->toBe($command->description);
    }
)->group('sitemeta-usecase', 'usecase');
