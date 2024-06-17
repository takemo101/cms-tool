<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Shared\ViewModel;

use ArrayObject;
use Takemo101\CmsTool\Http\ViewModel\ViewModel;

class ContentDetailPage extends ViewModel
{
    /**
     * constructor
     *
     * @param ArrayObject $content
     * @param bool $isDraft
     */
    public function __construct(
        public ArrayObject $content,
        public bool $isDraft = false,
    ) {
        //
    }
}
