<?php

namespace Takemo101\CmsTool\Http\Request\Admin;

use Symfony\Component\Validator\Constraints\NotBlank;

readonly class AuthorForChangeThemeMetaInputs
{
    public function __construct(
        #[NotBlank]
        public string $name = '',
        public ?string $link = null,
    ) {
        //
    }
}
