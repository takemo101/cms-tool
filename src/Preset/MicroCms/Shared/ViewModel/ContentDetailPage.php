<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Shared\ViewModel;

use ArrayObject;
use Closure;
use Takemo101\CmsTool\Http\ViewModel\ViewModel;

class ContentDetailPage extends ViewModel
{
    /**
     * constructor
     *
     * @param ArrayObject $content
     * @param Closure():array{0:?ArrayObject, 1:?ArrayObject} $prevAndNextContentsGenerator [0 => prev, 1 => next]
     * @param bool $isDraft
     */
    public function __construct(
        public readonly ArrayObject $content,
        private readonly Closure $prevAndNextContentsGenerator,
        public readonly bool $isDraft = false,
    ) {
        //
    }

    /**
     * Get a closure to retrieve the next and previous content.
     *
     * @return ContentDetailHelper
     */
    public function helper(): ContentDetailHelper
    {
        return new ContentDetailHelper($this->prevAndNextContentsGenerator);
    }
}
