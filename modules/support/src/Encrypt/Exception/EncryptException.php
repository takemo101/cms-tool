<?php

namespace CmsTool\Support\Encrypt\Exception;

use Exception;

class EncryptException extends Exception
{
    /**
     * Throw an exception when the encryption failed.
     *
     * @return self
     */
    public static function encryptionFailedError(): self
    {
        return new self('Encryption failed');
    }

    /**
     * Throw an exception when the JSON could not be encoded.
     *
     * @return self
     */
    public static function encodeJsonFailedError(): self
    {
        return new self('Could not encode JSON');
    }
}
