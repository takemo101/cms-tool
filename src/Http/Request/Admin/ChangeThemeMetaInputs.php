<?php

namespace Takemo101\CmsTool\Http\Request\Admin;

use EventSauce\ObjectHydrator\PropertyCasters\CastListToType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\All;

readonly class ChangeThemeMetaInputs
{
    public function __construct(
        #[NotBlank]
        public string $uid = '',
        #[NotBlank]
        public string $name = '',
        #[NotBlank]
        public string $version = '',
        #[All(constraints: [new Type(type: 'string')])]
        public array $images = [],
        #[All(constraints: [new Type(type: 'string')])]
        public array $tags = [],
        public ?string $link = null,
        public ?string $preset = null,
        #[Valid]
        public AuthorForChangeThemeMetaInputs $author = new AuthorForChangeThemeMetaInputs(),
        public bool $readonly = false,
        public array $extension = [],
        #[CastListToType(SchemaForChangeThemeMetaInputs::class)]
        #[Valid]
        public array $schema = [],
    ) {
        //
    }
}
