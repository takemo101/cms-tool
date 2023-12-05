<?php

namespace Takemo101\CmsTool\Domain\Admin;

use InvalidArgumentException;
use Takemo101\CmsTool\Domain\Shared\EmailAddress;
use Takemo101\CmsTool\Domain\Shared\HashedPassword;

abstract readonly class Admin
{
    /**
     * constructor
     *
     * @param AdminId $id
     * @param string $name
     * @param EmailAddress $email
     * @param HashedPassword $password
     * @throws InvalidArgumentException
     */
    public function __construct(
        public AdminId $id,
        public string $name,
        public EmailAddress $email,
        public HashedPassword $password,
    ) {
        assert(!empty($name), 'name is empty');
    }

    /**
     * Change account
     *
     * @param string $name
     * @param EmailAddress $email
     * @param HashedPassword|null $password
     * @return self
     */
    abstract public function changeAccount(
        string $name,
        EmailAddress $email,
        ?HashedPassword $password = null,
    ): self;

    /**
     * Whether it is a root administrator
     *
     * @return boolean
     */
    public function isRoot(): bool
    {
        return $this->id->isRoot();
    }
}
