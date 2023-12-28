<?php

namespace Takemo101\CmsTool\Http\ViewModel;

use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiAccessValidator;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Api\MicroCmsApiData;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaData;

class BasicSettingPage extends ViewModel
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
     * Whether API is effective
     *
     * @param MicroCmsApiAccessValidator $validator
     * @return boolean
     */
    public function isEnabledApi(
        MicroCmsApiAccessValidator $validator,
    ): bool {
        return $validator->validate(
            $this->api->toEntity(),
        );
    }
}
