<?php

namespace Takemo101\CmsTool\Support\Webhook;

use Takemo101\Chubby\Exception\Exceptions;
use Throwable;

/**
 * Exception for handling multiple exceptions that occurred in webhook handlers.
 */
class WebhookHandlingExceptions extends Exceptions
{
    public const Message = 'Multiple exceptions occurred in webhook handlers';

    /**
     * Throw if the given exceptions are not empty.
     *
     * @param Throwable ...$throwables The exceptions that occurred.
     * @return void|never
     */
    public static function throwIfNotEmpty(Throwable ...$throwables): void
    {
        if (empty($throwables)) {
            return;
        }

        throw new static(...$throwables);
    }
}
