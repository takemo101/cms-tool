<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\View\View;
use Takemo101\CmsTool\Http\ViewModel\BasicSettingPage;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Api\MicroCmsApiQueryService;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaQueryService;

class BasicSettingController
{
    /**
     * @param SiteMetaQueryService $siteMetaQueryService
     * @param MicroCmsApiQueryService $microCmsApiQueryService
     * @return View
     */
    public function editPage(
        SiteMetaQueryService $siteMetaQueryService,
        MicroCmsApiQueryService $microCmsApiQueryService,
    ): View {
        $meta = $siteMetaQueryService->get();
        $api = $microCmsApiQueryService->get();

        return view(
            'cms-tool::basic.edit',
            new BasicSettingPage(
                meta: $meta,
                api: $api,
            ),
        );
    }
}
