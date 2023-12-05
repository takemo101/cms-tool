<?php

namespace Takemo101\CmsTool\Infra\Hash;

use Takemo101\CmsTool\Domain\Shared\HashedPassword;
use Takemo101\CmsTool\Domain\Shared\PasswordHasher;
use Takemo101\CmsTool\Domain\Shared\PlainPassword;

class DefaultPasswordHasher implements PasswordHasher
{
    /**
     * {@inheritDoc}
     */
    public function hash(PlainPassword $plain): HashedPassword
    {
        return new HashedPassword(
            password_hash($plain->value(), PASSWORD_DEFAULT)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function verify(
        PlainPassword $plain,
        HashedPassword $hashed
    ): bool {
        return password_verify($plain->value(), $hashed->value());
    }
}
