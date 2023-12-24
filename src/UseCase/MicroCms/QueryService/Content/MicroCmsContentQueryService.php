<?php

namespace Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content;

use ArrayObject;
use Takemo101\CmsTool\UseCase\Shared\QueryService\Pager;

interface MicroCmsContentQueryService
{
    /**
     * Get the published content
     *
     * @param string $endpoint
     * @param string $id
     * @param MicroCmsContentGetOneQuery $query
     * @return ArrayObject|null
     */
    public function getOne(
        string $endpoint,
        string $id,
        MicroCmsContentGetOneQuery $query = new MicroCmsContentGetOneQuery(),
    ): ?ArrayObject;

    /**
     * Get the content during draft
     *
     * @param string $endpoint
     * @param string $id
     * @param string $draftKey
     * @param MicroCmsContentGetOneQuery $query
     * @return ArrayObject|null
     */
    public function getOneDraft(
        string $endpoint,
        string $id,
        string $draftKey,
        MicroCmsContentGetOneQuery $query = new MicroCmsContentGetOneQuery(),
    ): ?ArrayObject;

    /**
     * Get Microcms content list
     *
     * @param string $endpoint
     * @param Pager $pager
     * @param MicroCmsContentGetListQuery $query
     * @return MicroCmsContentGetListResult
     */
    public function getList(
        string $endpoint,
        Pager $pager = new Pager(),
        MicroCmsContentGetListQuery $query = new MicroCmsContentGetListQuery(),
    ): MicroCmsContentGetListResult;
}