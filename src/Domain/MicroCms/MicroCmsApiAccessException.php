<?php

namespace Takemo101\CmsTool\Domain\MicroCms;

use DomainException;

class MicroCmsApiAccessException extends DomainException
{
    /**
     * @var string
     */
    protected $message = 'The API key or service ID is invalid.';
}
