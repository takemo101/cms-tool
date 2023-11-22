<?php

namespace Takemo101\CmsTool\Domain\Admin;

use InvalidArgumentException;
use Takemo101\CmsTool\Domain\Shared\HashedPassword;

readonly class Admin
{
    /**
     * Whether it is a root administrator
     *
     * @var boolean
     */
    public const Root = false;

    /**
     * constructor
     *
     * @param string $name
     * @param HashedPassword $password
     * @throws InvalidArgumentException
     */
    public function __construct(
        public string $name,
        public HashedPassword $password,
    ) {
        assert(!empty($name), 'name is empty');
    }

    /**
     * Whether it is a root administrator
     *
     * @return boolean
     */
    public function isRoot(): bool
    {
        return static::Root;
    }
}
