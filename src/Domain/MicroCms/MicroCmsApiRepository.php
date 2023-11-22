<?php

namespace Takemo101\CmsTool\Domain\MicroCms;

interface MicroCmsApiRepository
{
    /**
     * @return boolean
     */
    public function has(): bool;

    /**
     * @return MicroCmsApi|null
     */
    public function get(): ?MicroCmsApi;

    /**
     * @param MicroCmsApi $api
     * @return void
     */
    public function save(MicroCmsApi $api): void;
}
