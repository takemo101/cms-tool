<?php

namespace Takemo101\CmsTool\Http\Request\Install;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

readonly class RootAdminForSaveBasicSettingRequest
{
    public function __construct(
        #[NotBlank]
        #[Length(min: 4, max: 50)]
        public string $name,

        #[NotBlank]
        #[Email]
        #[Length(max: 100)]
        public string $email,

        #[NotBlank]
        #[Length(min: 4, max: 50)]
        #[Regex(pattern: '/^[a-zA-Z0-9]+$/')]
        public string $password,
    ) {
        //
    }
}
