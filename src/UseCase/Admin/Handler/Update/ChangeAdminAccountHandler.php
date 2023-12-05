<?php

namespace Takemo101\CmsTool\UseCase\Admin\Handler\Update;

use Takemo101\CmsTool\Domain\Admin\Admin;
use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Domain\Shared\EmailAddress;
use Takemo101\CmsTool\Domain\Shared\PasswordHasher;
use Takemo101\CmsTool\Domain\Shared\PlainPassword;
use Takemo101\CmsTool\UseCase\Admin\Auth\AdminSession;
use Takemo101\CmsTool\UseCase\Shared\Exception\InstallSettingException;
use Takemo101\CmsTool\UseCase\Shared\Exception\NotFoundDataException;

class ChangeAdminAccountHandler
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
     * @param ChangeAdminAccountCommand $command
     * @param AdminSession $session
     * @return Admin
     * @throws InstallSettingException|NotFoundDataException
     */
    public function handle(ChangeAdminAccountCommand $command, AdminSession $session): Admin
    {
        $root = $this->repository->find();

        if (!$root) {
            throw InstallSettingException::notExistsSetting();
        }

        if (!$root->id->equals($session->getId())) {
            throw NotFoundDataException::notFoundData((string) $session->getId());
        }

        $changedRoot = $root->changeAccount(
            name: $command->name,
            email: new EmailAddress($command->email),
            password: $command->password
                ? $this->hasher->hash(
                    new PlainPassword($command->password)
                )
                : null,
        );

        $this->repository->save($changedRoot);

        return $root;
    }
}
