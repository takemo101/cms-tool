<?php

namespace Takemo101\CmsTool\UseCase\BasicSetting\Handler;

use Takemo101\CmsTool\Domain\Admin\RootAdmin;
use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Domain\Shared\EmailAddress;
use Takemo101\CmsTool\Domain\Shared\PasswordHasher;
use Takemo101\CmsTool\Domain\Shared\PlainPassword;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMeta;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMetaRepository;

class SaveBasicSettingHandler
{
    /**
     * constructor
     *
     * @param SiteMetaRepository $siteMetaRepository
     * @param RootAdminRepository $rootAdminRepository
     * @param PasswordHasher $hasher
     */
    public function __construct(
        private SiteMetaRepository $siteMetaRepository,
        private RootAdminRepository $rootAdminRepository,
        private PasswordHasher $hasher,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @param SaveBasicSettingCommand $command
     * @return array{0:SiteMeta,1:RootAdmin}
     */
    public function handle(SaveBasicSettingCommand $command): array
    {
        $meta = SiteMeta::install($command->siteName);

        $this->siteMetaRepository->save($meta);

        $root = new RootAdmin(
            name: $command->root->name,
            email: new EmailAddress($command->root->email),
            password: $this->hasher->hash(
                new PlainPassword($command->root->password),
            ),
        );

        $this->rootAdminRepository->save($root);

        return [$meta, $root];
    }
}
