<?php

namespace Takemo101\CmsTool\Http\Request\Admin;

use CmsTool\Theme\Schema\SchemaSettingId;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Regex;

readonly class SchemaForChangeThemeMetaInputs
{
    public function __construct(
        #[NotBlank]
        #[Regex(pattern: SchemaSettingId::Regex)]
        #[Length(max: 255)]
        public string $id = '',
        #[NotBlank]
        #[Length(max: 255)]
        public string $title = '',
        #[All(constraints: [
            new Type(type: 'array'),
            new Count(min: 1),
            new Collection(
                fields: [
                    'type' => new Required([
                        new Type(type: 'string'),
                        new NotBlank(),
                        new Choice(choices: [
                            'checkbox',
                            'color',
                            'header',
                            'number',
                            'select',
                            'textarea',
                            'text',
                            'editor',
                        ])
                    ]),
                    'id' => new Optional([
                        new Type(type: 'string'),
                        new NotBlank(),
                        new Regex(pattern: SchemaSettingId::Regex),
                        new Length(max: 255),
                    ]),
                ],
                allowExtraFields: true,
            )
        ])]
        public array $settings = [],
    ) {
        //
    }
}
