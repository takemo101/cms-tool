<?php

namespace Takemo101\CmsTool\Http\Request\Install;

use CmsTool\Support\Validation\FormRequest;
use CmsTool\Support\Validation\Mapper\CamelCaseMapper;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

#[CamelCaseMapper]
class SaveApiRequest extends FormRequest
{
    #[NotBlank]
    #[Length(max: 100)]
    public string $key;

    #[NotBlank]
    #[Length(max: 100)]
    public string $serviceId;
}
