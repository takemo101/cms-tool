<?php

namespace CmsTool\View;

use CmsTool\View\Contract\Htmlable;
use Stringable;
use Takemo101\Chubby\Contract\Renderable;

readonly class HtmlObject implements Htmlable
{
    /**
     * constructor
     *
     * @param string|Stringable|Renderable $html
     */
    public function __construct(
        private string|Stringable|Renderable $html,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        $html = $this->html;

        if ($html instanceof Stringable) {
            return $html->__toString();
        }

        if ($html instanceof Renderable) {
            return $html->render();
        }

        return $html;
    }
}
