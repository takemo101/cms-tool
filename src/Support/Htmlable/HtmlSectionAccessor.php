<?php

namespace Takemo101\CmsTool\Support\Htmlable;

use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObject;
use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObjectable;

class HtmlSectionAccessor
{
    /**
     * constructor
     *
     * @param HeadHtmls $head
     */
    public function __construct(
        private readonly HeadHtmls $head,
    ) {
        //
    }

    /**
     * @return ImmutableArrayObjectable&object{
     *    head: HeadHtmls
     * }
     */
    public function __invoke(): object
    {
        return ImmutableArrayObject::of([
            'head' => $this->head,
        ]);
    }
}
