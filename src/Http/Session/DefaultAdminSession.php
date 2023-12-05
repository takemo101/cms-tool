<?php

namespace Takemo101\CmsTool\Http\Session;

use Odan\Session\SessionInterface as Session;
use Takemo101\CmsTool\Domain\Admin\AdminId;
use Takemo101\CmsTool\UseCase\Admin\Auth\AdminSession;
use Takemo101\CmsTool\UseCase\Shared\Exception\UnauthorizedException;

class DefaultAdminSession implements AdminSession
{
    public const SessionKey = '__admin__';

    /**
     * constructor
     *
     * @param Session $session
     */
    public function __construct(
        private Session $session,
    ) {
        //
    }

    /**
     * @return boolean
     */
    public function isLoggedIn(): bool
    {
        return $this->session->has(self::SessionKey);
    }

    /**
     * Get the logged -in administrator ID
     * Throw an exception if you are not logged in
     *
     * @return AdminId
     * @throws UnauthorizedException
     */
    public function getId(): AdminId
    {
        /** @var string|null */
        $id = $this->session->get(self::SessionKey);

        if (!$id) {
            throw UnauthorizedException::notLoggedIn();
        }

        return new AdminId($id);
    }

    /**
     * @return void
     */
    public function login(AdminId $id): void
    {
        $this->session->set(self::SessionKey, $id->value());
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $this->session->delete(self::SessionKey);
    }
}
