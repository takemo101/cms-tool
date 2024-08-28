<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Shared\ViewModel;

use Takemo101\CmsTool\Http\ViewModel\ViewModel;
use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObjectable;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentGetListResult;
use Takemo101\CmsTool\UseCase\Shared\QueryService\ContentPaginator;

class TaxonomyIndexPage extends ViewModel
{
    /**
     * constructor
     *
     * @param ImmutableArrayObjectable<string,mixed> $taxonomy
     * @param MicroCmsContentGetListResult $result
     */
    public function __construct(
        public ImmutableArrayObjectable $taxonomy,
        private MicroCmsContentGetListResult $result,
    ) {
        //
    }

    /**
     * @return ImmutableArrayObjectable<string,mixed>[]
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
