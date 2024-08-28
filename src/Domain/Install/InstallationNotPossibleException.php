<?php

namespace Takemo101\CmsTool\Domain\Install;

use DomainException;

class InstallationNotPossibleException extends DomainException
{
    /**
     * @var string
     */
    protected $message = 'The installation cannot be completed because there is a setting that has not been completed.';
}
