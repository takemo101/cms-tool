<?php

namespace CmsTool\Support\Hash\Exception;

use Exception;
use Throwable;

class HashingException extends Exception
{
    /**
     * Throw an exception when the hashing failed.
     *
     * @param Throwable|null $previous
     * @return self
     */
    public static function hashingFailedError(
        ?Throwable $previous = null,
    ): self {
        return new self(
            message: 'Hashing failed',
            previous: $previous,
        );
    }
}
