<?php

namespace CmsTool\Theme\Exception;

use RuntimeException;

class ThemeLoadException extends RuntimeException
{
    /**
     * constructor
     *
     * @param string $path
     * @param string|null $message
     */
    public function __construct(
        private readonly string $path,
        ?string $message = null,
    ) {
        parent::__construct($message ?? "Failed to load theme: {$path}");
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
     * Create a new exception for when a theme is not found.
     *
     * @param string $path
     * @return self
     */
    public static function notFoundContent(string $path): self
    {
        return new self($path, "Theme content not found: {$path}");
    }

    /**
     * Create a new exception for decoding errors.
     *
     * @param string $path
     * @return self
     */
    public static function decodeError(string $path): self
    {
        return new self($path, "Failed to decode theme content: {$path}");
    }
}
