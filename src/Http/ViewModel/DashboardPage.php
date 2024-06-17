<?php

namespace Takemo101\CmsTool\Http\ViewModel;

use Takemo101\CmsTool\Domain\Publish\SitePublishRepository;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Api\MicroCmsApiData;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaData;

class DashboardPage extends ViewModel
{
    /**
     * constructor
     *
     * @param SiteMetaData $meta
     * @param MicroCmsApiData $api
     */
    public function __construct(
        public SiteMetaData $meta,
        public MicroCmsApiData $api,
    ) {
        //
    }

    /**
     * @return boolean
     */
    public function isPublished(
        SitePublishRepository $repository,
    ): bool {
        return $repository->isPublished();
    }
}
