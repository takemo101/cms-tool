<?php

namespace CmsTool\Theme\Exception;

use RuntimeException;
use Throwable;

class ThemeSaveException extends RuntimeException
{
    /**
     * constructor
     *
     * @param string $path
     * @param string|null $message
     * @param Throwable|null $previous
     * @param
     */
    public function __construct(
        private readonly string $path,
        ?string $message = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            message: $message ?? "Failed to save theme: {$path}",
            previous: $previous,
        );
    }

    /**
     * Get the id of the theme that was not found.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Create a new exception for encoding errors.
     *
     * @param string $path
     * @return self
     */
    public static function encodeError(
        string $path,
        ?Throwable $previous = null,
    ): self {
        return new self(
            path: $path,
            message: "Failed to encode theme content: {$path}",
            previous: $previous,
        );
    }

    /**
     * Create a new exception for json not accessible errors.
     *
     * @param string $path
     * @return self
     */
    public static function notWritableError(
        string $path,
        ?Throwable $previous = null,
    ): self {
        return new self(
            path: $path,
            previous: $previous,
        );
    }
}
