<?php

namespace CmsTool\Support\JsonAccess\Exception;

use Throwable;

class JsonConversionException extends JsonAccessException
{
    /**
     * Exception thrown when a JSON conversion error occurs.
     *
     * @param string         $path     The path of the JSON data.
     * @param Throwable|null $previous The previous exception used for the exception chaining.
     *
     * @return self
     */
    public static function decodeError(string $path, ?Throwable $previous = null): self
    {
        return new self(
            path: $path,
            message: sprintf('The json "%s" could not be decoded.', $path),
            previous: $previous,
        );
    }

    /**
     * Exception thrown when a JSON conversion error occurs.
     *
     * @param string         $path     The path of the JSON data.
     * @param Throwable|null $previous The previous exception used for the exception chaining.
     *
     * @return self
     */
    public static function encodeError(string $path, ?Throwable $previous = null): self
    {
        return new self(
            path: $path,
            message: sprintf('The json "%s" could not be encoded.', $path),
            previous: $previous,
        );
    }
}
