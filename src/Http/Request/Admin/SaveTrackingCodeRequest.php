<?php

namespace Takemo101\CmsTool\Http\Request\Admin;

use Symfony\Component\Validator\Constraints\Length;

readonly class SaveTrackingCodeRequest
{
    /**
     * constructor
     *
     * @param string $head
     * @param string $body
     * @param string $footer
     */
    public function __construct(
        #[Length(max: 5000)]
        public string $head,
        #[Length(max: 5000)]
        public string $body,
        #[Length(max: 5000)]
        public string $footer,
    ) {
        //
    }
}
