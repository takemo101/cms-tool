<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Blog;

use CmsTool\Support\Feed\FeedGenerator;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\Uri\ApplicationUri;
use Takemo101\CmsTool\Preset\Shared\Action\AbstractIndexAction;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentGetListQuery;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentQueryService;
use Takemo101\CmsTool\UseCase\Shared\QueryService\Pager;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaQueryService;

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

    /**
     * @param ServerRequestInterface $request
     * @param MicroCmsContentQueryService $contentQueryService
     * @param SiteMetaQueryService $siteMetaQueryService
     * @param ApplicationUri $uri
     * @param FeedGenerator $generator
     * @return BlogFeedRenderer
     */
    public function __invoke(
        ServerRequestInterface $request,
        MicroCmsContentQueryService $contentQueryService,
        SiteMetaQueryService $siteMetaQueryService,
        ApplicationUri $uri,
        FeedGenerator $generator,
    ): BlogFeedRenderer {
        $params = $request->getQueryParams();

        $result = $contentQueryService->getList(
            endpoint: $this->endpoint,
            pager: new Pager(
                page: $this->getPage($params),
                limit: $this->getLimit($params, $this->limit),
            ),
            query: new MicroCmsContentGetListQuery(
                orders: $this->order,
                q: $this->getQ($params),
                filters: $this->filter,
            )
        );

        return new BlogFeedRenderer(
            siteMeta: $siteMetaQueryService->get(),
            contents: $result->contents,
            generator: $generator,
            uri: $uri,
            order: $this->order,
        );
    }
}
