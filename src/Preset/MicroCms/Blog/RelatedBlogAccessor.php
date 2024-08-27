<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Blog;

use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObjectable;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentGetListQuery;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentQueryService;
use Takemo101\CmsTool\UseCase\Shared\QueryService\Pager;

class RelatedBlogAccessor
{
    /** @var integer */
    public const DefaultLimit = 3;

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
     * get related content
     *
     * @param string $id
     * @param integer $limit
     * @return ImmutableArrayObjectable<string,mixed>[]
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

        /** @var string[] */
        $tagIds = [];

        foreach ($tags as $tag) {
            $tagIds[] = $tag->id;
        }

        $query = new MicroCmsContentGetListQuery(
            filters: $this->buildQueryString(
                id: $id,
                categoryId: $category->id,
                tagIds: $tagIds,
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
     * @param string $categoryId
     * @param string[] $tagIds
     * @return string
     */
    private function buildQueryString(
        string $id,
        string $categoryId,
        array $tagIds,
    ): string {
        $excludeQuery = "id[not_equals]{$id}";

        $filters = [
            "{$this->fields->category}[equals]{$categoryId}",
        ];

        foreach ($tagIds as $tagId) {
            $filters[] = "{$this->fields->tag}[contains]{$tagId}";
        }

        $taxonomieQuery = implode('[and]', $filters);

        return "{$excludeQuery}[and]({$taxonomieQuery})";
    }
}
