<?php

namespace Takemo101\CmsTool\UseCase\Shared\Exception;

use Throwable;

class LoginFailedException extends UseCaseException
{
    /**
     * constructor
     *
     * @param string $message
     * @param integer $code
     * @param Throwable $previous
     * @return void
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
