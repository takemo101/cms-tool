<?php

namespace Takemo101\CmsTool\Http\Request\Admin;

use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

readonly class ChangeAdminAccountRequest
{
    public function __construct(
        #[NotBlank]
        #[Length(min: 4, max: 50)]
        public string $name,

        #[NotBlank]
        #[Email]
        #[Length(max: 100)]
        public string $email,

        #[AtLeastOneOf(
            [
                new Blank(),
                new Sequentially(
                    [
                        new Length(min: 4, max: 50),
                        new Regex(pattern: '/^[a-zA-Z0-9]+$/'),
                    ],
                ),
            ],
        )]
        public string $password,

        #[EqualTo(propertyPath: 'password')]
        public string $passwordConfirmation,
    ) {
        //
    }
}
