<?php

namespace Takemo101\CmsTool\Support\Htmlable;

use Takemo101\CmsTool\Support\Shared\ImmutableArrayObject;

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
     * @return ImmutableArrayObject&object{
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
