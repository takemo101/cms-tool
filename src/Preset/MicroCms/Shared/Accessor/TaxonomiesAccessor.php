<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Shared\Accessor;

use ArrayObject;
use CmsTool\Theme\Theme;
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
     */
    public function __construct(
        private MicroCmsContentQueryService $queryService,
        private string $endpoint,
        private ?string $format = null,
    ) {
        //
    }

    /**
     * @param integer $limit
     * @param string|null $id
     * @return ArrayObject[]
     */
    public function __invoke(int $limit = self::DefaultLimit, ?string $id = null): array
    {
        $format = $this->format;

        $query = new MicroCmsContentGetListQuery(
            filters: $format && $id ? sprintf($this->format, $id) : null,
        );

        return $this->queryService->getList(
            endpoint: $this->endpoint,
            pager: new Pager(limit: $limit),
            query: $query,

        )->contents;
    }
}
