<?php

namespace Takemo101\CmsTool\Infra\Saloon\QueryService;

use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentGetListQuery;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentGetListResult;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentGetOneQuery;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentQueryService;
use ArrayObject;
use Takemo101\CmsTool\Infra\Saloon\HttpClient\Get\MicroCmsGetListQuery;
use Takemo101\CmsTool\Infra\Saloon\HttpClient\Get\MicroCmsGetListRequest;
use Takemo101\CmsTool\Infra\Saloon\HttpClient\Get\MicroCmsGetOneQuery;
use Takemo101\CmsTool\Infra\Saloon\HttpClient\Get\MicroCmsGetOneRequest;
use Takemo101\CmsTool\Infra\Saloon\HttpClient\MicroCmsApiConnectorFactory;
use Takemo101\CmsTool\Infra\Shared\Exception\InfraException;
use Takemo101\CmsTool\Support\Shared\ImmutableArrayObject;
use Takemo101\CmsTool\UseCase\Shared\QueryService\ContentPaginator;
use Takemo101\CmsTool\UseCase\Shared\QueryService\Pager;

class SaloonMicroCmsContentQueryService implements MicroCmsContentQueryService
{
    /**
     * constructor
     *
     * @param MicroCmsApiConnectorFactory $factory
     */
    public function __construct(
        private MicroCmsApiConnectorFactory $factory,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function getOne(
        string $endpoint,
        string $id,
        MicroCmsContentGetOneQuery $query = new MicroCmsContentGetOneQuery(),
    ): ?ArrayObject {
        $connector = $this->factory->create();

        $response = $connector->send(
            new MicroCmsGetOneRequest(
                endpoint: $endpoint,
                id: $id,
                apiQuery: new MicroCmsGetOneQuery(
                    fields: $query->fields,
                    depth: $query->depth,
                )
            ),
        );

        if ($response->status() !== 200) {
            return null;
        }

        /** @var ImmutableArrayObject */
        $dto = $response->dto();

        return $dto;
    }

    /**
     * {@inheritDoc}
     */
    public function getOneDraft(
        string $endpoint,
        string $id,
        string $draftKey,
        MicroCmsContentGetOneQuery $query = new MicroCmsContentGetOneQuery(),
    ): ?ArrayObject {
        $connector = $this->factory->create();

        $response = $connector->send(
            new MicroCmsGetOneRequest(
                endpoint: $endpoint,
                id: $id,
                apiQuery: new MicroCmsGetOneQuery(
                    draftKey: $draftKey,
                    fields: $query->fields,
                    depth: $query->depth,
                ),
            ),
        );

        if ($response->status() !== 200) {
            return null;
        }

        /** @var ImmutableArrayObject */
        $dto = $response->dto();

        return $dto;
    }

    /**
     * {@inheritDoc}
     * @throws InfraException
     */
    public function getList(
        string $endpoint,
        Pager $pager = new Pager(),
        MicroCmsContentGetListQuery $query = new MicroCmsContentGetListQuery(),
    ): MicroCmsContentGetListResult {
        $connector = $this->factory->create();

        $response = $connector->send(
            new MicroCmsGetListRequest(
                endpoint: $endpoint,
                apiQuery: new MicroCmsGetListQuery(
                    limit: $pager->limit,
                    offset: $pager->offset,
                    orders: $query->orders,
                    q: $query->q,
                    fields: $query->fields,
                    ids: $query->ids,
                    filters: $query->filters,
                    depth: $query->depth,
                ),
            ),
        );

        if ($response->status() !== 200) {
            throw new InfraException('failed to get list');
        }

        /** @var array<string,mixed> */
        $json = $response->json();

        /** @var array<string,mixed> */
        $contents = $json['contents'] ?? [];

        /** @var ImmutableArrayObject[] */
        $contents = array_map(
            fn (array $content) => ImmutableArrayObject::of($content),
            $contents,
        );

        /** @var integer */
        $totalCount = $json['totalCount'] ?? 0;

        $totalPage = (int)ceil($totalCount / $pager->limit);

        return new MicroCmsContentGetListResult(
            contents: $contents,
            paginator: new ContentPaginator(
                totalCount: $totalCount,
                totalPage: $totalPage,
                currentPage: $pager->page,
                perPage: $pager->limit,
            ),
        );
    }
}
