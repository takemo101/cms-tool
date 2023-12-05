<?php

namespace Takemo101\CmsTool\Http\Request;

use CmsTool\Support\Validation\FormRequestObject;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * reference: https://dev.to/beganovich/validating-requests-in-the-symfony-app-2g0a
 */
readonly class TestChild
{
    public function __construct(
        #[NotBlank]
        public string $title,

        #[NotBlank]
        public string $body = 'body',
    ) {
        //
    }
}
