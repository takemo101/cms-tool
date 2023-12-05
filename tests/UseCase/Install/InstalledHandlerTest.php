<?php

use Takemo101\CmsTool\Domain\Install\InstallationNotPossibleException;
use Takemo101\CmsTool\Domain\Install\InstalledService;
use Takemo101\CmsTool\UseCase\Install\Handler\InstalledHandler;
use Mockery as m;
use Takemo101\CmsTool\Domain\Install\InstalledSpec;
use Takemo101\CmsTool\Domain\Install\InstallRepository;

describe(
    'InstalledHandler',
    function () {

        it(
            'should call the installed method of InstalledService',
            function () {
                // Create a mock for the InstalledService
                $repository = m::mock(InstallRepository::class);
                $repository->shouldReceive('isInstalled')
                    ->once()
                    ->andReturn(false);

                // Create an instance of the InstalledSpec
                $spec = m::mock(InstalledSpec::class, [
                    'isSatisfiedBy' => true,
                ])->makePartial();

                $service = new InstalledService($repository, $spec);

                // Create an instance of the InstalledHandler
                $handler = new InstalledHandler($service);

                // Call the handle method
                expect(fn () => $handler->handle())
                    ->not->toThrow(InstallationNotPossibleException::class);
            }
        );

        it(
            'should throw an exception if installation is not possible',
            function () {
                // Create a mock for the InstalledService
                $repository = m::mock(InstallRepository::class);

                // Create an instance of the InstalledSpec
                $spec = m::mock(InstalledSpec::class, [
                    'isSatisfiedBy' => false,
                ])->makePartial();

                $service = new InstalledService($repository, $spec);

                // Create an instance of the InstalledHandler
                $handler = new InstalledHandler($service);

                // Expect an exception to be thrown when calling the handle method
                expect(fn () => $handler->handle())
                    ->toThrow(InstallationNotPossibleException::class);
            }
        );
    }
)->group('install-usecase', 'usecase');
