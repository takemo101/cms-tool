<?php

namespace Takemo101\CmsTool\Error;

use Slim\Exception\HttpException;
use Throwable;

class SystemErrorPageRender extends AbstractErrorPageRender
{
    /**
     * {@inheritDoc}
     */
    protected function getErrorTemplateNames(Throwable $exception): array
    {
        $code = $exception instanceof HttpException
            ? $exception->getCode()
            : 500;

        // Cut the error code in 100 units
        $cutCode = floor($code / 100) * 100;

        return [
            "cms-tool::error.{$code}",
            "cms-tool::error.{$cutCode}",
            'cms-tool::error.default',
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function isMatchPath(
        string $requestPath,
        string $systemPath,
    ): bool {
        return str_starts_with($requestPath, $systemPath);
    }
}
