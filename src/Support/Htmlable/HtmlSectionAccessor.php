<?php

namespace Takemo101\CmsTool\Support\Htmlable;

use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObject;
use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObjectable;

/**
 * @phpstan-type SectionData = ImmutableArrayObjectable<string,mixed>&object{
 *    head: HeadHtmls
 * }
 */
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
     * @return SectionData
     */
    public function __invoke(): object
    {
        /** @var SectionData */
        $data = ImmutableArrayObject::of([
            'head' => $this->head,
        ]);

        return $data;
    }
}
