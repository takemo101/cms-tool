<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Blog;

use ArrayObject;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentGetListQuery;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentQueryService;
use Takemo101\CmsTool\UseCase\Shared\QueryService\Pager;

class RelatedBlogAccessor
{
    /** @var integer */
    public const DefaultLimit = 4;

    /**
     * constructor
     *
     * @param MicroCmsContentQueryService $queryService
     * @param string $endpoint
     * @param object{
     *  category: string,
     *  tag: string,
     * } $fields
     */
    public function __construct(
        private MicroCmsContentQueryService $queryService,
        private string $endpoint,
        private object $fields,
    ) {
        //
    }

    /**
     * Undocumented function
     *
     * @param string $id
     * @param integer $limit
     * @return ArrayObject[]
     */
    public function __invoke(
        string $id,
        int $limit = self::DefaultLimit,
    ): array {
        $content = $this->queryService->getOne(
            endpoint: $this->endpoint,
            id: $id,
        );

        if (!$content) {
            return [];
        }

        /** @var object{id:string}|null */
        $category = $content[$this->fields->category] ?? null;

        if (!$category) {
            return [];
        }

        /** @var object{id:string}[] */
        $tags = $content[$this->fields->tag] ?? [];

        $query = new MicroCmsContentGetListQuery(
            filters: $this->buildQueryString(
                id: $id,
                category: $category->id,
                tags: array_map(
                    fn (object $tag) => $tag->id,
                    $tags,
                ),
            ),
        );

        return $this->queryService->getList(
            endpoint: $this->endpoint,
            pager: new Pager(limit: $limit),
            query: $query,
        )->contents;
    }

    /**
     * build related content query
     *
     * @param string $id
     * @param string $category
     * @param string[] $tags
     * @return string
     */
    private function buildQueryString(
        string $id,
        string $category,
        array $tags,
    ): string {
        $excludeQuery = "id[not_equals]{$id}";

        $filters = [
            "{$this->fields->category}[equals]{$category}",
        ];

        foreach ($tags as $tag) {
            $filters[] = "{$this->fields->tag}[contains]{$tag}";
        }

        $taxonomieQuery = implode('[and]', $filters);

        return "{$excludeQuery}[and]({$taxonomieQuery})";
    }
}
