<?php

namespace Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content;

use ArrayObject;
use Takemo101\CmsTool\UseCase\Shared\QueryService\Pager;

interface MicroCmsContentQueryService
{
    /**
     * Get the published content of a single object.
     *
     * @param string $endpoint
     * @param MicroCmsContentGetOneQuery $query
     * @param bool $cache
     * @return ArrayObject|null
     */
    public function getSingle(
        string $endpoint,
        MicroCmsContentGetOneQuery $query = new MicroCmsContentGetOneQuery(),
        bool $cache = true,
    ): ?ArrayObject;

    /**
     * Get a draft of a single object
     *
     * @param string $endpoint
     * @param string $draftKey
     * @param MicroCmsContentGetOneQuery $query
     * @return ArrayObject|null
     */
    public function getSingleDraft(
        string $endpoint,
        string $draftKey,
        MicroCmsContentGetOneQuery $query = new MicroCmsContentGetOneQuery(),
    ): ?ArrayObject;

    /**
     * Get the published content.
     * Acquired by specifying id from the list.
     *
     * @param string $endpoint
     * @param string $id
     * @param MicroCmsContentGetOneQuery $query
     * @param bool $cache
     * @return ArrayObject|null
     */
    public function getOne(
        string $endpoint,
        string $id,
        MicroCmsContentGetOneQuery $query = new MicroCmsContentGetOneQuery(),
        bool $cache = true,
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
     * @param bool $cache
     * @return MicroCmsContentGetListResult
     */
    public function getList(
        string $endpoint,
        Pager $pager = new Pager(),
        MicroCmsContentGetListQuery $query = new MicroCmsContentGetListQuery(),
        bool $cache = true,
    ): MicroCmsContentGetListResult;
}
