<?php

namespace CmsTool\View;

use CmsTool\View\Contract\Htmlable;

readonly class HtmlObject implements Htmlable
{
    /**
     * constructor
     *
     * @param string $html
     */
    public function __construct(
        private string $html,
    ) {
        //
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->html;
    }
}
