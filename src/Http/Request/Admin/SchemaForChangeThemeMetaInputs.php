<?php

namespace Takemo101\CmsTool\Http\Request\Admin;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Required;

readonly class SchemaForChangeThemeMetaInputs
{
    public function __construct(
        #[NotBlank]
        public string $id = '',
        #[NotBlank]
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
                        ])
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
