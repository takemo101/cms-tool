<?php

namespace Takemo101\CmsTool\UseCase\Shared\Exception;

class InstallSettingException extends UseCaseException
{
    /**
     * @return self
     */
    public static function notExistsSetting(): self
    {
        return new self('There is no installation settings.');
    }
}
