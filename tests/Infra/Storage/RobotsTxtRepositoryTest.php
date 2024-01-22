<?php

use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\CmsTool\Infra\Storage\Repository\RobotsTxtRepository;
use Mockery as m;

describe(
    'RobotsTxtRepository',
    function () {
        it('should get robots.txt content', function () {
            // Create a mock for the LocalFilesystem
            $filesystem = m::mock(LocalFilesystem::class);
            $filesystem->shouldReceive('exists')->once()->andReturn(true);
            $filesystem->shouldReceive('read')->once()->andReturn('User-agent: *');

            // Create an instance of the RobotsTxtRepository
            $repository = new RobotsTxtRepository($filesystem);

            // Call the get method
            $content = $repository->get();

            // Assert that the content is correct
            expect($content)->toBe('User-agent: *');
        });

        it('should return null if robots.txt does not exist', function () {
            // Create a mock for the LocalFilesystem
            $filesystem = m::mock(LocalFilesystem::class);
            $filesystem->shouldReceive('exists')->once()->andReturn(false);

            // Create an instance of the RobotsTxtRepository
            $repository = new RobotsTxtRepository($filesystem);

            // Call the get method
            $content = $repository->get();

            // Assert that the content is null
            expect($content)->toBeNull();
        });

        it('should save robots.txt content', function () {
            // Create a mock for the LocalFilesystem
            $filesystem = m::mock(LocalFilesystem::class);
            $filesystem->shouldReceive('write')->once();

            // Create an instance of the RobotsTxtRepository
            $repository = new RobotsTxtRepository($filesystem);

            // Call the save method
            $repository->save('User-agent: *');

            // Assert that the write method was called with the correct arguments
            $filesystem->shouldHaveReceived('write')->with('robots.txt', 'User-agent: *');
        });

        it('should delete robots.txt', function () {
            // Create a mock for the LocalFilesystem
            $filesystem = m::mock(LocalFilesystem::class);
            $filesystem->shouldReceive('exists')->once()->andReturn(true);
            $filesystem->shouldReceive('delete')->once();

            // Create an instance of the RobotsTxtRepository
            $repository = new RobotsTxtRepository($filesystem);

            // Call the delete method
            $repository->delete();

            // Assert that the delete method was called with the correct arguments
            $filesystem->shouldHaveReceived('delete')->with('robots.txt');
        });
    }
)->group('infra', 'RobotsTxtRepository');
