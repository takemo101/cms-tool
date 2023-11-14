<?php

namespace CmsTool\Support\JsonAccess\Exception;

use Throwable;

final class NotFoundJsonException extends JsonAccessException
{
    public function __construct(string $path, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            path: $path,
            message: sprintf('The json data "%s" was not found.', $path),
            code: $code,
            previous: $previous
        );
    }
}
