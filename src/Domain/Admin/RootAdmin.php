<?php

namespace Takemo101\CmsTool\Domain\Admin;

use Takemo101\CmsTool\Domain\Shared\EmailAddress;
use Takemo101\CmsTool\Domain\Shared\HashedPassword;
use InvalidArgumentException;

readonly class RootAdmin extends Admin
{
    /**
     * constructor
     *
     * @param string $name
     * @param EmailAddress $email
     * @param HashedPassword $password
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $name,
        EmailAddress $email,
        HashedPassword $password,
    ) {
        parent::__construct(
            id: AdminId::root(),
            name: $name,
            email: $email,
            password: $password,
        );
    }

    /**
     * Change account
     *
     * @param string $name
     * @param EmailAddress $email
     * @param HashedPassword|null $password
     * @return self
     */
    public function changeAccount(
        string $name,
        EmailAddress $email,
        ?HashedPassword $password = null,
    ): self {
        return new self(
            name: $name,
            email: $email,
            // If the password is not specified, the current password will be used
            password: $password ?? $this->password,
        );
    }
}
