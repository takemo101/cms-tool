<?php

namespace Takemo101\CmsTool\UseCase\Admin\Handler\Login;

use Takemo101\CmsTool\Domain\Admin\Admin;
use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Domain\Shared\EmailAddress;
use Takemo101\CmsTool\Domain\Shared\PasswordHasher;
use Takemo101\CmsTool\Domain\Shared\PlainPassword;
use Takemo101\CmsTool\UseCase\Admin\Auth\AdminSession;
use Takemo101\CmsTool\UseCase\Shared\Exception\LoginFailedException;

class LoginAdminHandler
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
     * @param LoginAdminCommand $command
     * @param AdminSession $session
     * @return Admin
     * @throws LoginFailedException
     */
    public function handle(LoginAdminCommand $command, AdminSession $session): Admin
    {
        $root = $this->repository->find();

        if (!$root) {
            throw LoginFailedException::notFound();
        }

        $email = new EmailAddress($command->email);
        $plain = new PlainPassword($command->password);

        if (!$root->email->equals($email)) {
            throw LoginFailedException::invalidEmail();
        }

        if (!$this->hasher->verify($plain, $root->password)) {
            throw LoginFailedException::invalidPassword();
        }

        $session->login($root->id);

        return $root;
    }
}
