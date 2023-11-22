<?php

namespace Takemo101\CmsTool\UseCase\Admin\Handler;

use Takemo101\CmsTool\Domain\Admin\RootAdmin;
use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Domain\Shared\PasswordHasher;
use Takemo101\CmsTool\Domain\Shared\PlainPassword;

readonly class SaveRootAdminHandler
{
    /**
     * constructor
     *
     * @param RootAdminRepository $repository
     * @param PasswordHasher $hasher
     */
    public function __construct(
        private RootAdminRepository $repository,
        private PasswordHasher $hasher,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @param SaveRootAdminCommand $command
     * @return void
     */
    public function handle(SaveRootAdminCommand $command): void
    {
        $root = new RootAdmin(
            name: $command->name,
            password: $this->hasher->hash(
                new PlainPassword($command->password),
            ),
        );

        $this->repository->save($root);
    }
}
