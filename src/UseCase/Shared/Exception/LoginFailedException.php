<?php

namespace Takemo101\CmsTool\UseCase\Shared\Exception;

class LoginFailedException extends UseCaseException
{
    /**
     * @return self
     */
    public static function notFound(): self
    {
        return new self('Admin not found');
    }

    /**
     * @return self
     */
    public static function invalidPassword(): self
    {
        return new self('Invalid password');
    }

    /**
     * @return self
     */
    public static function invalidEmail(): self
    {
        return new self('Invalid email');
    }
}
