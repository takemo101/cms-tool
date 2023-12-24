<?php

namespace Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content;

readonly class MicroCmsContentGetOneQuery
{
    /**
     * constructor
     *
     * @param string[]|string|null $fields
     * @param string|null $depth
     */
    public function __construct(
        public array|string|null $fields = null,
        public ?int $depth = null,
    ) {
        //
    }
}
