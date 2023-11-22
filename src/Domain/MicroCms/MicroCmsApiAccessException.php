<?php

namespace Takemo101\CmsTool\Domain\MicroCms;

use DomainException;

final class MicroCmsApiAccessException extends DomainException
{
    protected $message = 'The API key or service ID is invalid.';
}
