<?php

namespace CmsTool\View\Component;

use LogicException;

class ComponentException extends LogicException
{
    public const NotFoundErrorCode = 1;

    public const AlreadyExistsErrorCode = 2;

    /**
     * The component name not found
     *
     * @param string $name
     * @return static
     */
    public static function notFound(string $name): self
    {
        return new self(
            message: "The component name not found: {$name}",
            code: self::NotFoundErrorCode,
        );
    }

    /**
     * The component name already exists
     *
     * @param string $name
     * @return static
     */
    public static function alreadyExists(string $name): self
    {
        return new self(
            message: "The component name already exists: {$name}",
            code: self::AlreadyExistsErrorCode,
        );
    }
}
