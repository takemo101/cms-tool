<?php

namespace Takemo101\CmsTool\Http\Request\Install;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Valid;

readonly class SaveBasicSettingRequest
{
    public function __construct(
        #[NotBlank]
        #[Length(max: 50)]
        public string $siteName,

        #[Valid]
        public RootAdminForSaveBasicSettingRequest $root,
    ) {
        //
    }
}
