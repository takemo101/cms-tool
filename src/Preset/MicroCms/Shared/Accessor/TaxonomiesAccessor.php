<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Shared\Accessor;

use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObjectable;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentGetListQuery;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentQueryService;
use Takemo101\CmsTool\UseCase\Shared\QueryService\Pager;

class TaxonomiesAccessor
{
    /** @var integer */
    public const DefaultLimit = 50;

    /**
     * constructor
     *
     * @param MicroCmsContentQueryService $queryService
     * @param string $endpoint
     * @param string|null $format Sprintf format string for filters
     * @param string|null $orders Default orders
     */
    public function __construct(
        private MicroCmsContentQueryService $queryService,
        private string $endpoint,
        private ?string $format = null,
        private ?string $orders = null,
    ) {
        //
    }

    /**
     * @param integer $limit
     * @param string|null $id
     * @param string|null $orders
     * @return ImmutableArrayObjectable<string,mixed>[]
     */
    public function __invoke(int $limit = self::DefaultLimit, ?string $id = null, ?string $orders = null): array
    {
        $format = $this->format;

        $query = new MicroCmsContentGetListQuery(
            orders: $orders ?? $this->orders,
            filters: $format && $id ? sprintf($format, $id) : null,
        );

        return $this->queryService->getList(
            endpoint: $this->endpoint,
            pager: new Pager(limit: $limit),
            query: $query,
        )->contents;
    }
}
