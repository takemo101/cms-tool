<?php

namespace Takemo101\CmsTool\Http\Request;

use CmsTool\Support\Validation\FormRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * reference: https://dev.to/beganovich/validating-requests-in-the-symfony-app-2g0a
 */
final class TestRequest extends FormRequest
{
    #[NotBlank(message: 'title is required')]
    public string $title;

    #[NotBlank]
    public string $body;

    #[Valid]
    public TestChild $child;
}
