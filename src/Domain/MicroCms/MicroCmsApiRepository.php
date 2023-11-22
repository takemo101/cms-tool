<?php

namespace Takemo101\CmsTool\Domain\MicroCms;

interface MicroCmsApiRepository
{
    /**
     * @return boolean
     */
    public function exists(): bool;

    /**
     * @return MicroCmsApi|null
     */
    public function find(): ?MicroCmsApi;

    /**
     * @param MicroCmsApi $api
     * @return void
     */
    public function save(MicroCmsApi $api): void;
}
