<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Shared\Action;

use CmsTool\View\View;
use CmsTool\View\ViewCreator;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\CmsTool\Preset\Shared\Action\AbstractIndexable;
use Takemo101\CmsTool\Preset\Shared\Exception\NotFoundThemeTemplateException;
use Takemo101\CmsTool\Preset\Shared\LayeredTemplateNamesCreator;
use Takemo101\CmsTool\Preset\MicroCms\Shared\ViewModel\TaxonomyIndexPage;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentGetListQuery;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentQueryService;
use Takemo101\CmsTool\UseCase\Shared\QueryService\Pager;

class TaxonomyIndexAction extends AbstractIndexable
{
    /**
     * constructor
     *
     * @param TaxonomyIndexActionEndpoints $endpoints
     * @param string $relation
     * @param string $signature
     * @param integer $limit
     * @param string|null $order
     * @param boolean $multiple
     */
    public function __construct(
        private readonly TaxonomyIndexActionEndpoints $endpoints,
        private readonly string $relation,
        private readonly string $signature,
        private readonly int $limit = 10,
        private readonly ?string $order = null,
        private readonly bool $multiple = false,
    ) {
        assert(
            empty($relation) === false,
            'The relation must not be empty',
        );

        assert(
            empty($signature) === false,
            'The signature must not be empty',
        );

        assert(
            $limit > 0,
            'The limit must be greater than 0',
        );

        if ($order !== null) {
            assert(
                empty($order) === false,
                'The order must not be empty',
            );
        }
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
            endpoint: $this->endpoints->taxonomy,
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
            endpoint: $this->endpoints->content,
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
            new TaxonomyIndexPage(
                taxonomy: $taxonomy,
                result: $result,
            ),
        ) ?? throw new NotFoundThemeTemplateException($templateNames);
    }
}
