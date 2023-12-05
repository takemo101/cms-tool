<?php

namespace Takemo101\CmsTool\UseCase\Admin\Auth;

use Takemo101\CmsTool\Domain\Admin\AdminId;
use Takemo101\CmsTool\UseCase\Shared\Exception\UnauthorizedException;

interface AdminSession
{
    /**
     * @return boolean
     */
    public function isLoggedIn(): bool;

    /**
     * Get the logged -in administrator ID
     * Throw an exception if you are not logged in
     *
     * @return AdminId
     * @throws UnauthorizedException
     */
    public function getId(): AdminId;

    /**
     * @return void
     */
    public function login(AdminId $id): void;

    /**
     * @return void
     */
    public function logout(): void;
}
