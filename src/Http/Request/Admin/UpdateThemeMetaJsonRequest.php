<?php

namespace Takemo101\CmsTool\Http\Request\Admin;

use Symfony\Component\Validator\Constraints\NotBlank;

readonly class UpdateThemeMetaJsonRequest
{
    public function __construct(
        #[NotBlank]
        public string $meta,
    ) {
        //
    }

    /**
     * @return array<string,mixed>|null
     */
    public function getMetaArray(): ?array
    {
        return json_decode($this->meta, true);
    }
}
