<?php

namespace CmsTool\Support\JsonAccess\Exception;

use Throwable;

final class JsonConversionException extends JsonAccessException
{
    /**
     * Exception thrown when a JSON conversion error occurs.
     *
     * @param string         $path     The path of the JSON data.
     * @param Throwable|null $previous The previous exception used for the exception chaining.
     *
     * @return static
     */
    public static function decodeError(string $path, ?Throwable $previous = null): static
    {
        return new static(
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
     * @return static
     */
    public static function encodeError(string $path, ?Throwable $previous = null): static
    {
        return new static(
            path: $path,
            message: sprintf('The json "%s" could not be encoded.', $path),
            previous: $previous,
        );
    }
}
