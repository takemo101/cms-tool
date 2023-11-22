<?php

namespace Takemo101\CmsTool\Http\Request\Install;

use CmsTool\Support\Validation\FormRequest;
use CmsTool\Support\Validation\Mapper\CamelCaseMapper;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;


#[CamelCaseMapper]
class SaveAccountRequest extends FormRequest
{
    #[NotBlank]
    #[Length(min: 4, max: 50)]
    public string $name;

    #[NotBlank]
    #[Length(min: 4, max: 50)]
    #[Regex(pattern: '/^[a-zA-Z0-9]+$/')]
    public string $password;
}
