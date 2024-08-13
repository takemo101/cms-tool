<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Blog;

use CmsTool\Support\Feed\Feed;
use CmsTool\Support\Feed\FeedAuthor;
use CmsTool\Support\Feed\FeedCategories;
use CmsTool\Support\Feed\FeedGenerator;
use CmsTool\Support\Feed\FeedItem;
use CmsTool\Support\Feed\FeedItems;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\Uri\ApplicationUri;
use Takemo101\CmsTool\Preset\Shared\Action\AbstractIndexAction;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentGetListQuery;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentQueryService;
use Takemo101\CmsTool\UseCase\Shared\QueryService\Pager;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaQueryService;
use DateTimeImmutable;

/**
 * @phpstan-type ContentData = object{
 *   title: string,
 *   publishedAt?: string,
 *   updatedAt: string,
 *   content: string,
 *   id: string,
 *   category?: object{
 *     name: string,
 *   }
 * }
 */
class BlogFeedAction extends AbstractIndexAction
{
    /**
     * constructor
     *
     * @param string $endpoint
     * @param integer $limit
     * @param string $order
     * @param string|null $filter
     */
    public function __construct(
        private readonly string $endpoint,
        private readonly int $limit = 20,
        private readonly string $order = 'publishedAt',
        private readonly ?string $filter = null,
    ) {
        assert(
            empty($endpoint) === false,
            'The endpoint must not be empty',
        );

        assert(
            $limit > 0,
            'The limit must be greater than 0',
        );

        assert(
            empty($order) === false,
            'The order must not be empty',
        );
    }


    public function __invoke(
        ServerRequestInterface $request,
        MicroCmsContentQueryService $contentQueryService,
        SiteMetaQueryService $siteMetaQueryService,
        ApplicationUri $uri,
        FeedGenerator $generator,
        ResponseFactoryInterface $responseFactory,
    ): ResponseInterface {
        $params = $request->getQueryParams();

        $order = $this->order;

        $result = $contentQueryService->getList(
            endpoint: $this->endpoint,
            pager: new Pager(
                page: $this->getPage($params),
                limit: $this->getLimit($params, $this->limit),
            ),
            query: new MicroCmsContentGetListQuery(
                orders: $order,
                q: $this->getQ($params),
                filters: $this->filter,
            )
        );

        /**
         * @var object|null $latest
         */
        $latest = $result->contents[0] ?? null;

        $updated = new DateTimeImmutable($latest?->{$order} ?? null);

        $siteMeta = $siteMetaQueryService->get();

        $items = collect($result->contents)
            ->map(
                /**
                 * @param ContentData $content
                 */
                fn(object $content) => new FeedItem(
                    title: $content->title,
                    published: new DateTimeImmutable($content->{$order} ?? $updated),
                    content: $content->content,
                    link: $uri->withPath(
                        route('blog.detail', [
                            'id' => $content->id,
                        ]),
                    ),
                    guid: $content->id,
                    author: new FeedAuthor(
                        name: $siteMeta->name,
                    ),
                    categories: $content->category
                        ? new FeedCategories(
                            $content->category->name,
                        )
                        : new FeedCategories(),
                ),
            )
            ->all();

        $response = $responseFactory->createResponse()
            ->withHeader(
                'Content-Type',
                $generator->getOutputMeta()
                    ->contentType
            );

        $response->getBody()->write(
            $generator->generate(
                new Feed(
                    title: $siteMeta->seo->title ?: $siteMeta->name,
                    description: $siteMeta->seo->description ?: $siteMeta->seo->title ?: $siteMeta->name,
                    link: $uri->getBase(),
                    updated: $updated,
                    copyright: $siteMeta->name,
                    items: new FeedItems(...$items),
                ),
            ),
        );

        return $response;
    }
}
