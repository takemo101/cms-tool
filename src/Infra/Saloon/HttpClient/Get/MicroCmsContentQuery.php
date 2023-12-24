<?php

namespace Takemo101\CmsTool\Infra\Saloon\HttpClient\Get;

readonly class MicroCmsContentQuery
{
    /**
     * constructor
     *
     * @param string|null $draftKey
     * @param integer|null $limit
     * @param integer|null $offset
     * @param string[]|string|null $orders
     * @param string[]|string|null $q
     * @param string[]|string|null $fields
     * @param string[]|string|null $ids
     * @param string|null $filters
     * @param string|null $depth
     */
    public function __construct(
        public ?string $draftKey = null,
        public ?int $limit = null,
        public ?int $offset = null,
        public array|string|null $orders = null,
        public array|string|null $q = null,
        public array|string|null $fields = null,
        public array|string|null $ids = null,
        public ?string $filters = null,
        public ?int $depth = null,
    ) {
        //
    }

    /**
     * @return string|null
     */
    private function getDraftKeyQuery(): ?string
    {
        $draftKey = $this->draftKey;

        return empty($draftKey) ? null : $draftKey;
    }

    /**
     * @return integer|null
     */
    private function getLimitQuery(): ?int
    {
        $limit = $this->limit;

        if (is_null($limit)) {
            return null;
        }

        return $limit > 0 ? $limit : null;
    }

    private function getOffsetQuery(): ?int
    {
        $offset = $this->offset;

        if (is_null($offset)) {
            return null;
        }

        return $offset < 0 ? null : $offset;
    }

    /**
     * @return string|null
     */
    private function getOrdersQuery(): ?string
    {
        $orders = $this->orders;

        if (empty($orders)) {
            return null;
        }

        if (is_string($orders)) {
            return $orders;
        }

        return implode(',', $orders);
    }

    /**
     * @return string|null
     */
    private function getQQuery(): ?string
    {
        $q = $this->q;

        if (empty($q)) {
            return null;
        }

        if (is_string($q)) {
            return $this->q;
        }

        return implode(',', $q);
    }

    /**
     * @return string|null
     */
    private function getFieldsQuery(): ?string
    {
        $fields = $this->fields;

        if (empty($fields)) {
            return null;
        }

        if (is_string($fields)) {
            return $this->fields;
        }

        return implode(',', $fields);
    }

    /**
     * @return string|null
     */
    private function getIdsQuery(): ?string
    {
        $ids = $this->ids;

        if (empty($ids)) {
            return null;
        }

        if (is_string($ids)) {
            return $this->ids;
        }

        return implode(',', $ids);
    }

    /**
     * @return string|null
     */
    private function getFiltersQuery(): ?string
    {
        $filters = $this->filters;

        return empty($filters) ? null : $filters;
    }

    /**
     * @return integer|null
     */
    private function getDepthQuery(): ?int
    {
        $depth = $this->depth;

        if (is_null($depth)) {
            return null;
        }

        if ($depth < 1) {
            return 1;
        }

        if ($depth > 3) {
            return 3;
        }

        return $depth;
    }

    /**
     * @return array<string,mixed>
     */
    public function toQuery(): array
    {
        /** @var array<string,mixed> */
        $result = array_filter(
            [
                'draftKey' => $this->getDraftKeyQuery(),
                'limit' => $this->getLimitQuery(),
                'offset' => $this->getOffsetQuery(),
                'orders' => $this->getOrdersQuery(),
                'q' => $this->getQQuery(),
                'fields' => $this->getFieldsQuery(),
                'ids' => $this->getIdsQuery(),
                'filters' => $this->getFiltersQuery(),
                'depth' => $this->getDepthQuery(),
            ],
        );

        return $result;
    }
}
