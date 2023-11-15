<?php

namespace CmsTool\Session\Csrf;

use Slim\Exception\HttpSpecializedException;

class HttpCsrfTokenMismatchException extends HttpSpecializedException
{
    /**
     * @var integer
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'The CSRF token is invalid.';

    /**
     * @var string
     */
    protected string $title = '400 Bad Request';

    /**
     * @var string
     */
    protected string $description = 'The CSRF token is invalid. Please try to resubmit the form.';
}
