<?php

namespace Takemo101\CmsTool\UseCase\SiteMeta\QueryService;

readonly class SiteMetaData
{
    /**
     * constructor
     *
     * @param string $name
     * @param SiteSeoData $seo
     */
    public function __construct(
        public string $name,
        public SiteSeoData $seo,
    ) {
        //
    }
}
