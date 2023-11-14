<?php

namespace CmsTool\Support\JsonAccess\Exception;

use Exception;
use Throwable;

abstract class JsonAccessException extends Exception
{
    /**
     * constructor
     *
     * @param string $path
     * @param string $message
     * @param integer $code
     * @param Throwable|null $previous
     */
    public function __construct(
        private readonly string $path,
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            message: $message,
            code: $code,
            previous: $previous
        );
    }

    /**
     * Returns the path of the JSON file that caused the exception.
     *
     * @return string The path of the JSON file.
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
