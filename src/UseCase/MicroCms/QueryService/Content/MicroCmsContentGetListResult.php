<?php

namespace Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content;

use ArrayObject;
use Takemo101\CmsTool\UseCase\Shared\QueryService\ContentPaginator;

readonly class MicroCmsContentGetListResult
{
    /**
     * constructor
     *
     * @param ArrayObject[] $contents
     * @param ContentPaginator $paginator
     */
    public function __construct(
        public array $contents,
        public ContentPaginator $paginator,
    ) {
        //
    }
}
