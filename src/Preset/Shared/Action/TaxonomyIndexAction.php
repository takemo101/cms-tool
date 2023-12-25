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

class TaxonomyIndexAction extends AbstractIndexAction
{
    /**
     * constructor
     *
     * @param string $taxonomyEndpoint
     * @param string $contentEndpoint
     * @param string $relation
     * @param string $signature
     * @param integer $limit
     * @param string|null $order
     * @param boolean $multiple
     */
    public function __construct(
        private readonly string $taxonomyEndpoint,
        private readonly string $contentEndpoint,
        private readonly string $relation,
        private readonly string $signature,
        private readonly int $limit = 10,
        private readonly ?string $order = null,
        private readonly bool $multiple = false,
    ) {
        //
    }

    /**
     * @param ServerRequestInterface $request
     * @param MicroCmsContentQueryService $queryService
     * @param ViewCreator $creator
     * @param LayeredTemplateNamesCreator $names
     * @param string $id
     * @return View
     * @throws HttpNotFoundException|NotFoundThemeTemplateException
     */
    public function __invoke(
        ServerRequestInterface $request,
        MicroCmsContentQueryService $queryService,
        ViewCreator $creator,
        LayeredTemplateNamesCreator $names,
        string $id,
    ): View {
        $taxonomy = $queryService->getOne(
            endpoint: $this->taxonomyEndpoint,
            id: $id,
        );

        if (!$taxonomy) {
            throw new HttpNotFoundException(
                request: $request,
                message: 'Taxonomy not found',
            );
        }

        $params = $request->getQueryParams();

        $operator = $this->multiple ? 'contains' : 'equals';

        $result = $queryService->getList(
            endpoint: $this->contentEndpoint,
            pager: new Pager(
                page: $this->getPage($params),
                limit: $this->getLimit($params, $this->limit),
            ),
            query: new MicroCmsContentGetListQuery(
                orders: $this->getOrder($params, $this->order),
                q: $this->getQ($params),
                filters: "{$this->relation}[{$operator}]{$id}",
            )
        );

        $templateNames = $names->taxonomy($this->signature, $id);

        return $creator->createIfExists(
            $templateNames,
            new ContentIndexPage($result),
        ) ?? throw new NotFoundThemeTemplateException($templateNames);
    }
}
