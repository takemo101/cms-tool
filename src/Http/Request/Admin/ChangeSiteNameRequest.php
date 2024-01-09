<?php

namespace Takemo101\CmsTool\Http\Request\Admin;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

readonly class ChangeSiteNameRequest
{
    public function __construct(
        #[NotBlank]
        #[Length(max: 50)]
        public string $siteName,
    ) {
        //
    }
}
