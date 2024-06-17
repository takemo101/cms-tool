<?php

namespace Takemo101\CmsTool\Http\ViewModel;

use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaData;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteSeoData;

class SiteSeoPage extends ViewModel
{
    /**
     * constructor
     *
     * @param SiteMetaData $meta
     */
    public function __construct(
        public SiteMetaData $meta,
    ) {
        //
    }

    /**
     * @return SiteSeoData
     */
    public function seo(): SiteSeoData
    {
        return $this->meta->seo;
    }
}
