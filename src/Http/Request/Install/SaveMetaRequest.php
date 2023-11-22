<?php

namespace Takemo101\CmsTool\Http\Request\Install;

use CmsTool\Support\Validation\FormRequest;
use CmsTool\Support\Validation\Mapper\CamelCaseMapper;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

#[CamelCaseMapper]
final class SaveMetaRequest extends FormRequest
{
    #[NotBlank]
    #[Length(max: 50)]
    public string $name;

    #[NotBlank]
    #[Length(max: 50)]
    public string $title;

    #[Length(max: 200)]
    public ?string $description = null;
}
