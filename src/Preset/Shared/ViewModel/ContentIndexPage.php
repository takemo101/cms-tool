<?php

namespace Takemo101\CmsTool\Preset\Shared\ViewModel;

use ArrayObject;
use Takemo101\CmsTool\Http\ViewModel\ViewModel;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentGetListResult;
use Takemo101\CmsTool\UseCase\Shared\QueryService\ContentPaginator;

class ContentIndexPage extends ViewModel
{
    /**
     * constructor
     *
     * @param MicroCmsContentGetListResult $result
     */
    public function __construct(
        private MicroCmsContentGetListResult $result,
    ) {
        //
    }

    /**
     * @return ArrayObject[]
     */
    public function contents(): array
    {
        return $this->result->contents;
    }

    /**
     * @return ContentPaginator
     */
    public function paginator(): ContentPaginator
    {
        return $this->result->paginator;
    }
}
