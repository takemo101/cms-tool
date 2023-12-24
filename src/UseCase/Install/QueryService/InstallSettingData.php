<?php

namespace Takemo101\CmsTool\UseCase\Install\QueryService;

use Takemo101\CmsTool\UseCase\Admin\QueryService\RootAdminAccountData;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Api\MicroCmsApiData;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaData;

readonly class InstallSettingData
{
    /**
     * constructor
     *
     * @param boolean $installed
     * @param MicroCmsApiData|null $api
     * @param RootAdminAccountData|null $root
     * @param SiteMetaData|null $meta
     */
    public function __construct(
        public bool $installed = false,
        public ?MicroCmsApiData $api = null,
        public ?RootAdminAccountData $root = null,
        public ?SiteMetaData $meta = null,
    ) {
        //
    }

    /**
     * @return boolean
     */
    public function canBeInstalled(): bool
    {
        return $this->isArrangedApi()
            && $this->isArrangedBasicSetting();
    }

    /**
     * @return boolean
     */
    public function isArrangedApi(): bool
    {
        return $this->api !== null;
    }

    /**
     * @return boolean
     */
    public function isArrangedBasicSetting(): bool
    {
        return $this->root !== null
            && $this->meta !== null;
    }
}
