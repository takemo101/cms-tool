<?php

namespace Takemo101\CmsTool\Domain\Install;

use DomainException;

final class InstallationNotPossibleException extends DomainException
{
    protected $message = 'The installation cannot be completed because there is a setting that has not been completed.';
}
