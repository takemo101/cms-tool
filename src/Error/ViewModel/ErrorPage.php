<?php

namespace Takemo101\CmsTool\Error\ViewModel;

use Slim\Exception\HttpException;
use Takemo101\CmsTool\Http\ViewModel\ViewModel;
use Throwable;

class ErrorPage extends ViewModel
{
    /** @var string */
    public readonly string $message;

    /**
     * constructor
     *
     * @param Throwable $e
     */
    public function __construct(
        public readonly Throwable $e,
    ) {
        $this->message = $e->getMessage();
    }

    public function title(): string
    {
        return $this->e instanceof HttpException
            ? $this->e->getTitle()
            : '500 Internal Server Error';
    }

    public function description(): string
    {
        return $this->e instanceof HttpException
            ? $this->e->getDescription()
            : 'Unexpected condition encountered preventing server from fulfilling request.';
    }

    public function code(): int
    {
        return $this->e instanceof HttpException
            ? $this->e->getCode()
            : 500;
    }
}
