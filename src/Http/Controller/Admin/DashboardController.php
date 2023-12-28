<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use Takemo101\CmsTool\Http\ViewModel\DashboardPage;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Api\MicroCmsApiQueryService;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaQueryService;

class DashboardController
{
    /**
     * Tool dashboard page
     *
     * @param MicroCmsApiQueryService $microCmsApiQueryService
     * @param SiteMetaQueryService $siteMetaQueryService
     * @return View
     */
    public function dashboard(
        MicroCmsApiQueryService $microCmsApiQueryService,
        SiteMetaQueryService $siteMetaQueryService,
    ) {
        return view('cms-tool::dashboard', new DashboardPage(
            meta: $siteMetaQueryService->get(),
            api: $microCmsApiQueryService->get(),
        ));
    }
}
