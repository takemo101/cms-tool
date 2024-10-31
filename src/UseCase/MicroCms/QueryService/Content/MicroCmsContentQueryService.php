<?php

namespace Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content;

use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObjectable;
use Takemo101\CmsTool\UseCase\Shared\QueryService\Pager;

interface MicroCmsContentQueryService
{
    /**
     * Get the published content of a single object.
     *
     * @param string $endpoint
     * @param MicroCmsContentGetOneQuery $query
     * @return ImmutableArrayObjectable<string,mixed>|null
     */
    public function getSingle(
        string $endpoint,
        MicroCmsContentGetOneQuery $query = new MicroCmsContentGetOneQuery(),
    ): ?ImmutableArrayObjectable;

    /**
     * Get a draft of a single object
     *
     * @param string $endpoint
     * @param string $draftKey
     * @param MicroCmsContentGetOneQuery $query
     * @return ImmutableArrayObjectable<string,mixed>|null
     */
    public function getSingleDraft(
        string $endpoint,
        string $draftKey,
        MicroCmsContentGetOneQuery $query = new MicroCmsContentGetOneQuery(),
    ): ?ImmutableArrayObjectable;

    /**
     * Get the published content.
     * Acquired by specifying id from the list.
     *
     * @param string $endpoint
     * @param string $id
     * @param MicroCmsContentGetOneQuery $query
     * @return ImmutableArrayObjectable<string,mixed>|null
     */
    public function getOne(
        string $endpoint,
        string $id,
        MicroCmsContentGetOneQuery $query = new MicroCmsContentGetOneQuery(),
    ): ?ImmutableArrayObjectable;

    /**
     * Get the content during draft
     *
     * @param string $endpoint
     * @param string $id
     * @param string $draftKey
     * @param MicroCmsContentGetOneQuery $query
     * @return ImmutableArrayObjectable<string,mixed>|null
     */
    public function getOneDraft(
        string $endpoint,
        string $id,
        string $draftKey,
        MicroCmsContentGetOneQuery $query = new MicroCmsContentGetOneQuery(),
    ): ?ImmutableArrayObjectable;

    /**
     * Get the first content
     *
     * @param string $endpoint
     * @param MicroCmsContentGetListQuery $query
     * @return ImmutableArrayObjectable<string,mixed>|null
     */
    public function getFirst(
        string $endpoint,
        MicroCmsContentGetListQuery $query = new MicroCmsContentGetListQuery(),
    ): ?ImmutableArrayObjectable;

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
