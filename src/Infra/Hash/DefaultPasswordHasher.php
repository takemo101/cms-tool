<?php

namespace Takemo101\CmsTool\Infra\Hash;

use Takemo101\CmsTool\Domain\Shared\HashedPassword;
use Takemo101\CmsTool\Domain\Shared\PasswordHasher;
use Takemo101\CmsTool\Domain\Shared\PlainPassword;

class DefaultPasswordHasher implements PasswordHasher
{
    /**
     * Hash the password
     *
     * @param PlainPassword $plain
     * @return HashedPassword
     */
    public function hash(PlainPassword $plain): HashedPassword
    {
        return new HashedPassword(
            password_hash($plain->value, PASSWORD_DEFAULT)
        );
    }

    /**
     * Verify the matching of the password
     *
     * @param PlainPassword $plain
     * @param HashedPassword $hashed
     * @return boolean
     */
    public function verify(
        PlainPassword $plain,
        HashedPassword $hashed
    ): bool {
        return password_verify($plain->value, $hashed->value);
    }
}
