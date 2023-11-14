<?php

namespace CmsTool\Support\Encrypt\Exception;

use Exception;

class DecryptException extends Exception
{
    /**
     * Throw an exception when the decryption failed.
     *
     * @return self
     */
    public static function decryptionFailedError(): self
    {
        return new self('Decryption failed');
    }

    /**
     * Throw an exception when the payload is invalid.
     *
     * @return self
     */
    public static function invalidPayloadError(): self
    {
        return new self('The payload is invalid');
    }

    /**
     * Throw an exception when the JSON could not be decoded.
     *
     * @return self
     */
    public static function decodeJsonFailedError(): self
    {
        return new self('Could not decode JSON');
    }
}
