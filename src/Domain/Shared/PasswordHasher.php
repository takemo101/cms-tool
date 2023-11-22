<?php

namespace Takemo101\CmsTool\Domain\Shared;

interface PasswordHasher
{
    /**
     * Hash the password
     *
     * @param PlainPassword $plain
     * @return HashedPassword
     */
    public function hash(PlainPassword $plain): HashedPassword;

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
    ): bool;
}
