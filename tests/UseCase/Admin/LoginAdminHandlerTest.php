<?php

use Takemo101\CmsTool\Domain\Admin\Admin;
use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Domain\Shared\EmailAddress;
use Takemo101\CmsTool\Domain\Shared\PasswordHasher;
use Takemo101\CmsTool\UseCase\Admin\Auth\AdminSession;
use Takemo101\CmsTool\UseCase\Admin\Handler\Login\LoginAdminCommand;
use Takemo101\CmsTool\UseCase\Admin\Handler\Login\LoginAdminHandler;
use Takemo101\CmsTool\UseCase\Shared\Exception\LoginFailedException;
use Mockery as m;
use Takemo101\CmsTool\Domain\Admin\RootAdmin;
use Takemo101\CmsTool\Domain\Shared\HashedPassword;

it(
    'should login an admin',
    function () {
        // Create a mock for the RootAdminRepository
        $repository = m::mock(RootAdminRepository::class);
        $repository->shouldReceive('find')->andReturn(new RootAdmin(
            name: 'John Doe',
            email: new EmailAddress('test@test.com'),
            password: new HashedPassword('hashed_password')
        ));

        // Create a mock for the PasswordHasher
        $hasher = m::mock(PasswordHasher::class);
        $hasher->shouldReceive('verify')->andReturn(true);

        // Create a mock for the AdminSession
        $session = m::mock(AdminSession::class);
        $session->shouldReceive('login')->once();

        // Create an instance of the LoginAdminHandler
        $handler = new LoginAdminHandler($repository, $hasher);

        // Create a mock for the LoginAdminCommand
        $command = new LoginAdminCommand(
            email: 'test@test.com',
            password: 'password'
        );

        // Call the handle method
        $actual = $handler->handle($command, $session);

        // Assert that the returned admin is correct
        expect($actual)->toBeInstanceOf(Admin::class);
    }
)->group('login-admin-handler', 'admin-usecase', 'usecase');

it(
    'should throw LoginFailedException when root admin not found',
    function () {
        // Create a mock for the RootAdminRepository
        $repository = m::mock(RootAdminRepository::class);
        $repository->shouldReceive('find')->andReturn(null);

        // Create a mock for the PasswordHasher
        $hasher = m::mock(PasswordHasher::class);

        // Create a mock for the AdminSession
        $session = m::mock(AdminSession::class);

        // Create an instance of the LoginAdminHandler
        $handler = new LoginAdminHandler($repository, $hasher);

        // Create a mock for the LoginAdminCommand
        $command = new LoginAdminCommand(
            email: 'test@test.com',
            password: 'password'
        );

        // Call the handle method and expect it to throw LoginFailedException
        expect(fn () => $handler->handle($command, $session))
            ->toThrow(LoginFailedException::class);
    }
)->group('login-admin-handler', 'admin-usecase', 'usecase');

it(
    'should throw LoginFailedException when email is invalid',
    function () {
        // Create a mock for the RootAdminRepository
        $repository = m::mock(RootAdminRepository::class);
        $repository->shouldReceive('find')->andReturn(new RootAdmin(
            name: 'John Doe',
            email: new EmailAddress('test@test.com'),
            password: new HashedPassword('hashed_password')
        ));

        // Create a mock for the PasswordHasher
        $hasher = m::mock(PasswordHasher::class);

        // Create a mock for the AdminSession
        $session = m::mock(AdminSession::class);

        // Create an instance of the LoginAdminHandler
        $handler = new LoginAdminHandler($repository, $hasher);

        // Create a mock for the LoginAdminCommand with invalid email
        $command = new LoginAdminCommand(
            email: 'invalid@test.com',
            password: 'password'
        );

        // Call the handle method and expect it to throw LoginFailedException
        expect(fn () => $handler->handle($command, $session))
            ->toThrow(LoginFailedException::class);
    }
)->group('login-admin-handler', 'admin-usecase', 'usecase');

it(
    'should throw LoginFailedException when password is invalid',
    function () {
        // Create a mock for the RootAdminRepository
        $repository = m::mock(RootAdminRepository::class);
        $repository->shouldReceive('find')->andReturn(new RootAdmin(
            name: 'John Doe',
            email: new EmailAddress('test@test.com'),
            password: new HashedPassword('hashed_password')
        ));

        // Create a mock for the PasswordHasher
        $hasher = m::mock(PasswordHasher::class);
        $hasher->shouldReceive('verify')->andReturn(false);

        // Create a mock for the AdminSession
        $session = m::mock(AdminSession::class);

        // Create an instance of the LoginAdminHandler
        $handler = new LoginAdminHandler($repository, $hasher);

        // Create a mock for the LoginAdminCommand with invalid password
        $command = new LoginAdminCommand(
            email: 'test@test.com',
            password: 'invalid_password'
        );

        // Call the handle method and expect it to throw LoginFailedException
        expect(fn () => $handler->handle($command, $session))
            ->toThrow(LoginFailedException::class);
    }
)->group('login-admin-handler', 'admin-usecase', 'usecase');
