<?php

namespace CmsTool\Theme\Exception;

use RuntimeException;
use Throwable;

class ThemeSaveException extends RuntimeException
{
    /**
     * Error code for encoding errors.
     */
    public const EncodeErrorCode = 1;

    /**
     * Error code for json not accessible errors.
     */
    public const NotWritableErrorCode = 2;

    /**
     * constructor
     *
     * @param string $path
     * @param string|null $message
     * @param Throwable|null $previous
     */
    public function __construct(
        private readonly string $path,
        ?string $message = null,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            message: $message ?? "Failed to save theme content: {$path}",
            code: $code,
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
            code: self::EncodeErrorCode,
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
            message: "Failed to write theme content: {$path}",
            code: self::NotWritableErrorCode,
            previous: $previous,
        );
    }
}
