<?php

namespace Takemo101\CmsTool\Preset\Shared\ViewModel;

use ArrayObject;
use Takemo101\CmsTool\Http\ViewModel\ViewModel;
use Takemo101\CmsTool\UseCase\Shared\QueryService\ContentPaginator;

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
