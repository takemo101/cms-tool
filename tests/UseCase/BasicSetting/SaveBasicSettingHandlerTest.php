<?php

use Takemo101\CmsTool\Domain\Admin\RootAdmin;
use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Domain\Shared\EmailAddress;
use Takemo101\CmsTool\Domain\Shared\PasswordHasher;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMeta;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMetaRepository;
use Takemo101\CmsTool\UseCase\BasicSetting\Handler\SaveBasicSettingCommand;
use Takemo101\CmsTool\UseCase\BasicSetting\Handler\SaveBasicSettingHandler;
use Mockery as m;
use Takemo101\CmsTool\Domain\Shared\HashedPassword;
use Takemo101\CmsTool\UseCase\BasicSetting\Handler\RootAdminForSaveBasicSettingCommand;

it(
    'should save basic settings',
    function () {
        // Create a mock for the SiteMetaRepository
        $siteMetaRepository = m::mock(SiteMetaRepository::class);
        $siteMetaRepository->shouldReceive('save')->once();

        // Create a mock for the RootAdminRepository
        $rootAdminRepository = m::mock(RootAdminRepository::class);
        $rootAdminRepository->shouldReceive('save')->once();

        $hashedPassword = new HashedPassword('hashed_password');

        // Create a mock for the PasswordHasher
        $hasher = m::mock(PasswordHasher::class);
        $hasher->shouldReceive('hash')
            ->once()
            ->andReturn($hashedPassword);

        // Create an instance of the SaveBasicSettingHandler
        $handler = new SaveBasicSettingHandler(
            $siteMetaRepository,
            $rootAdminRepository,
            $hasher
        );

        // Create a mock for the SaveBasicSettingCommand
        $command = new SaveBasicSettingCommand(
            siteName: 'Sample Site',
            root: new RootAdminForSaveBasicSettingCommand(
                name: 'John Doe',
                email: 'test@test.com',
                password: 'password',
            )
        );

        // Call the handle method
        $actual = $handler->handle($command);

        // Assert that the SiteMeta and RootAdmin objects are created correctly
        $expectedSiteMeta = SiteMeta::install(
            name: $command->siteName,
        );
        $expectedRootAdmin = new RootAdmin(
            name: $command->root->name,
            email: new EmailAddress($command->root->email),
            password: $hashedPassword,
        );

        expect($actual)->toEqual([$expectedSiteMeta, $expectedRootAdmin]);
    }
)->group('basic-setting-usecase', 'usecase');
