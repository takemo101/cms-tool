<?php

namespace Takemo101\CmsTool\Http\Request\Admin;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

readonly class ChangeThemeMetaInputs
{
    public function __construct(
        #[NotBlank]
        public string $uid = '',

        #[NotBlank]
        public string $name = '',

        #[NotBlank]
        public string $version = '',

        public array $images = [],
        public array $tags = [],
        public ?string $link = null,
        public ?string $preset = null,

        #[Valid]
        public AuthorForChangeThemeMetaInputs $author = new AuthorForChangeThemeMetaInputs(),

        public bool $readonly = false,
        public array $extension = [],
    ) {
        //
    }
}
