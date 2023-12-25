<?php

namespace Takemo101\CmsTool\Preset\Shared\Action;

use CmsTool\View\View;
use CmsTool\View\ViewCreator;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\CmsTool\Preset\Shared\Exception\NotFoundThemeTemplateException;
use Takemo101\CmsTool\Preset\Shared\LayeredTemplateNamesCreator;
use Takemo101\CmsTool\Preset\Shared\ViewModel\ContentIndexPage;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentGetListQuery;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentQueryService;
use Takemo101\CmsTool\UseCase\Shared\QueryService\Pager;

class ContentIndexAction extends AbstractIndexAction
{
    /**
     * constructor
     *
     * @param string $endpoint
     * @param string $signature
     * @param integer $limit
     * @param string|null $order
     * @param string|null $filter
     */
    public function __construct(
        private readonly string $endpoint,
        private readonly string $signature,
        private readonly int $limit = 10,
        private readonly ?string $order = null,
        private readonly ?string $filter = null,
    ) {
        //
    }

    /**
     * @param ServerRequestInterface $request
     * @param MicroCmsContentQueryService $queryService
     * @param ViewCreator $creator
     * @param LayeredTemplateNamesCreator $names
     * @return View
     * @throws NotFoundThemeTemplateException
     */
    public function __invoke(
        ServerRequestInterface $request,
        MicroCmsContentQueryService $queryService,
        ViewCreator $creator,
        LayeredTemplateNamesCreator $names,
    ): View {
        $params = $request->getQueryParams();

        $result = $queryService->getList(
            endpoint: $this->endpoint,
            pager: new Pager(
                page: $this->getPage($params),
                limit: $this->getLimit($params, $this->limit),
            ),
            query: new MicroCmsContentGetListQuery(
                orders: $this->getOrder($params, $this->order),
                q: $this->getQ($params),
                filters: $this->filter,
            )
        );

        $templateNames = $names->index($this->signature);

        return $creator->createIfExists(
            $templateNames,
            new ContentIndexPage($result),
        ) ?? throw new NotFoundThemeTemplateException($templateNames);
    }
}
