<?php

namespace Takemo101\CmsTool\Http\Request\Admin;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Takemo101\CmsTool\Domain\Shared\PlainPassword;

readonly class LoginRequest
{
    public function __construct(
        #[NotBlank]
        #[Email]
        public string $email,

        #[NotBlank]
        #[Length(min: PlainPassword::MinLenth, max: PlainPassword::MaxLength)]
        #[Regex(pattern: '/^[a-zA-Z0-9]+$/')]
        public string $password,
    ) {
        //
    }
}
