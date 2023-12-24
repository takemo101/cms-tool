<?php

namespace Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content;

readonly class MicroCmsContentGetListQuery
{
    /**
     * constructor
     *
     * @param string[]|string|null $orders
     * @param string[]|string|null $q
     * @param string[]|string|null $fields
     * @param string[]|string|null $ids
     * @param string|null $filters
     * @param string|null $depth
     */
    public function __construct(
        public array|string|null $orders = null,
        public array|string|null $q = null,
        public array|string|null $fields = null,
        public array|string|null $ids = null,
        public ?string $filters = null,
        public ?int $depth = null,
    ) {
        //
    }
}
