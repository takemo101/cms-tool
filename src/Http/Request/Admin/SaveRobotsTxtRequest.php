<?php

namespace Takemo101\CmsTool\Http\Request\Admin;

use Symfony\Component\Validator\Constraints\Length;

readonly class SaveRobotsTxtRequest
{
    /**
     * constructor
     *
     * @param string $content
     */
    public function __construct(
        #[Length(max: 10000)]
        public string $content,
    ) {
        //
    }
}
