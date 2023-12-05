<?php

namespace Takemo101\CmsTool\Http\Exception;

use Slim\Exception\HttpSpecializedException;

class HttpAuthorizedException extends HttpSpecializedException
{
    /**
     * @var int
     */
    protected $code = 403;

    /**
     * @var string
     */
    protected $message = 'Forbidden.';

    protected string $title = '403 Forbidden';
    protected string $description = 'The server understood the request but refuses to authorize it.';
}
