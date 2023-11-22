<?php

namespace CmsTool\Support\JsonAccess\Exception;

use Throwable;

class JsonNotAccessibleException extends JsonAccessException
{
    /**
     * Exception thrown when a JSON data is not readable.
     *
     * @param string $path The path to the JSON file.
     * @param Throwable|null $previous
     * @return static
     */
    public static function notReadableError(string $path, ?Throwable $previous = null): static
    {
        return new static(
            path: $path,
            message: sprintf('The json "%s" is not readable.', $path),
            previous: $previous,
        );
    }

    /**
     * Exception thrown when a JSON data is not writable.
     *
     * @param string $path The path of the JSON file.
     * @param Throwable|null $previous
     * @return static
     */
    public static function notWritableError(string $path, ?Throwable $previous = null): static
    {
        return new static(
            path: $path,
            message: sprintf('The json "%s" is not writable.', $path),
            previous: $previous,
        );
    }
}
