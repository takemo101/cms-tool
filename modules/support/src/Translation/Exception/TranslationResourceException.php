<?php

namespace CmsTool\Support\Translation\Exception;

use Exception;

class TranslationResourceException extends Exception
{
    /**
     * Exceptions when it is not necessary resources
     *
     * @param string $type
     * @return self
     */
    public static function invalidResourceType(
        string $type
    ): self {
        return new self(
            message: "Translation resource must be a {$type}"
        );
    }
}
