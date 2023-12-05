<?php

namespace Takemo101\CmsTool\UseCase\SiteMeta\QueryService;

interface SiteMetaQueryService
{
    /**
     * @return SiteMetaData
     */
    public function get(): SiteMetaData;
}
