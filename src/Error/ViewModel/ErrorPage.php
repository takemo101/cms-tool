<?php

namespace Takemo101\CmsTool\Error\ViewModel;

use Slim\Exception\HttpException;
use Takemo101\CmsTool\Http\ViewModel\ViewModel;
use Throwable;

class ErrorPage extends ViewModel
{
    /** @var string */
    public readonly string $message;

    /** @var string */
    public readonly int $code;

    public function __construct(
        public readonly Throwable $e,
    ) {
        $this->message = $e->getMessage();
        $this->code = $e instanceof HttpException
            ? $e->getCode()
            : 500;
    }
}
