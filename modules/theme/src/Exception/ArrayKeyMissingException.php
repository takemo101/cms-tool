<?php

namespace CmsTool\Theme\Exception;

use InvalidArgumentException;

class ArrayKeyMissingException extends InvalidArgumentException
{
    /**
     * constructor
     *
     * @param string $key
     * @param string|null $message
     */
    public function __construct(
        private readonly string $key,
        ?string $message = null,
    ) {
        parent::__construct($message ?? "Array key '{$key}' is missing");
    }

    /**
     * Get the key that was not found.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Throw exception with key.
     *
     * @param string $key
     * @return never
     */
    public static function throw(string $key): never
    {
        throw new self($key);
    }
}
