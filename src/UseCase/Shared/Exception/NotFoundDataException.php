<?php

namespace Takemo101\CmsTool\UseCase\Shared\Exception;

class NotFoundDataException extends UseCaseException
{
    /**
     * @return self
     */
    public static function notFoundData(string $id): self
    {
        return new self("Not found data: {$id}");
    }
}
