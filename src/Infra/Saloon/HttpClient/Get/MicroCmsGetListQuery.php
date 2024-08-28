<?php

namespace Takemo101\CmsTool\Infra\Saloon\HttpClient\Get;

class MicroCmsGetListQuery
{
    /** @var MicroCmsContentQuery */
    private MicroCmsContentQuery $query;

    /**
     * constructor
     *
     * @param integer|null $limit
     * @param integer|null $offset
     * @param string[]|string|null $orders
     * @param string[]|string|null $q
     * @param string[]|string|null $fields
     * @param string[]|string|null $ids
     * @param string|null $filters
     * @param integer|null $depth
     */
    public function __construct(
        public ?int $limit = null,
        public ?int $offset = null,
        public array|string|null $orders = null,
        public array|string|null $q = null,
        public array|string|null $fields = null,
        public array|string|null $ids = null,
        public ?string $filters = null,
        public ?int $depth = null,
    ) {
        $this->query = new MicroCmsContentQuery(
            limit: $limit,
            offset: $offset,
            orders: $orders,
            q: $q,
            fields: $fields,
            ids: $ids,
            filters: $filters,
            depth: $depth,
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function toQuery(): array
    {
        return $this->query->toQuery();
    }
}
