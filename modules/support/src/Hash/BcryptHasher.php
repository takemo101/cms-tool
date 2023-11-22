<?php

namespace CmsTool\Support\Hash;

use CmsTool\Support\Hash\Exception\HashingException;
use DI\Attribute\Inject;
use Error;

class BcryptHasher implements Hasher
{
    /**
     * constructor
     *
     * @param integer $cost
     */
    public function __construct(
        #[Inject('config.support.hash.cost')]
        private int $cost = 10,
    ) {
        //
    }

    /**
     * Generate hash value
     *
     * @param string $value
     * @return string
     * @throws HashingException
     */
    public function hash(string $value): string
    {
        try {
            $hashed = password_hash($value, PASSWORD_BCRYPT, [
                'cost' => $this->cost,
            ]);
        } catch (Error $e) {
            throw HashingException::hashingFailedError($e);
        }

        return $hashed;
    }

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
    ): bool {
        return password_verify($plain, $hashed);
    }
}
