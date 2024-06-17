<?php

namespace Takemo101\CmsTool\UseCase\Shared\Exception;

class UnauthorizedException extends UseCaseException
{
    /**
     * @return self
     */
    public static function notLoggedIn(): self
    {
        return new self('Not logged in');
    }
}
