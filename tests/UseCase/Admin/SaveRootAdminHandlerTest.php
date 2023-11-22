<?php

use Takemo101\CmsTool\Domain\Admin\RootAdmin;
use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Domain\Shared\HashedPassword;
use Takemo101\CmsTool\Domain\Shared\PasswordHasher;
use Takemo101\CmsTool\UseCase\Admin\Handler\SaveRootAdminCommand;
use Takemo101\CmsTool\UseCase\Admin\Handler\SaveRootAdminHandler;
use Mockery as m;

it(
    'should save a RootAdmin',
    function () {
        // Create a mock for the RootAdminRepository
        $repository = m::mock(RootAdminRepository::class);
        $repository->shouldReceive('save')->once();

        $hashedPassword = new HashedPassword('hashed_password');

        // Create a mock for the PasswordHasher
        $hasher = m::mock(PasswordHasher::class);
        $hasher->shouldReceive('hash')
            ->once()
            ->andReturn($hashedPassword);

        // Create an instance of the SaveRootAdminHandler
        $handler = new SaveRootAdminHandler($repository, $hasher);

        // Create a mock for the SaveRootAdminCommand
        $command = new SaveRootAdminCommand('John Doe', 'password');

        // Call the handle method
        $actual = $handler->handle($command);

        // Assert that the RootAdmin object is created correctly
        $expectedRootAdmin = new RootAdmin(
            name: 'John Doe',
            password: $hashedPassword,
        );

        expect($actual)->toEqual($expectedRootAdmin);
    }
)->group('admin-usecase', 'usecase');
