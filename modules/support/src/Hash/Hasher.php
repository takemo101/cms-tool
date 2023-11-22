<?php

namespace CmsTool\Support\Hash;

use CmsTool\Support\Hash\Exception\HashingException;

interface Hasher
{
    /**
     * Haveshir
     *
     * @param string $value
     * @return string
     * @throws HashingException
     */
    public function hash(string $value): string;

    /**
     * Verify the match value of the hash value
     *
     * @param string $plain
     * @param string $hashed
     * @return boolean
     */
    public function verify(
        string $plain,
        string $hashed,
    ): bool;
}
