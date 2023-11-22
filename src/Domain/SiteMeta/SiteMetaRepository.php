<?php

namespace Takemo101\CmsTool\Domain\SiteMeta;

interface SiteMetaRepository
{
    /**
     * @return boolean
     */
    public function has(): bool;

    /**
     * @param SiteMeta $meta
     * @return void
     */
    public function save(SiteMeta $meta): void;
}
