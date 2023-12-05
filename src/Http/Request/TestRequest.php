<?php

namespace Takemo101\CmsTool\Http\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * reference: https://dev.to/beganovich/validating-requests-in-the-symfony-app-2g0a
 */
readonly class TestRequest
{
    public function __construct(
        #[NotBlank(message: 'title is required')]
        public string $mainTitle,

        #[NotBlank]
        public string $body,

        #[Valid]
        public TestChild $child,
    ) {
        //
    }
}
