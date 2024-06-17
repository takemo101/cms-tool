<?php

namespace Takemo101\CmsTool\Http\Request\Install;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

readonly class SaveMicroCmsApiRequest
{
    public function __construct(
        #[NotBlank]
        #[Length(max: 100)]
        public string $key,
        #[NotBlank]
        #[Length(max: 100)]
        public string $serviceId,
    ) {
        //
    }
}
