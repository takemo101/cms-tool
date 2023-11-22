<?php

namespace Takemo101\CmsTool\UseCase\Shared\QueryService;

readonly class InstallSettingData
{
    /**
     * constructor
     *
     * @param boolean $installed
     * @param MicroCmsApiData|null $api
     * @param RootAdminData|null $root
     * @param SiteMetaData|null $meta
     */
    public function __construct(
        public bool $installed = false,
        public ?MicroCmsApiData $api = null,
        public ?RootAdminData $root = null,
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
            && $this->isArrangedRoot()
            && $this->isArrangedMeta();
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
    public function isArrangedRoot(): bool
    {
        return $this->root !== null;
    }

    /**
     * @return boolean
     */
    public function isArrangedMeta(): bool
    {
        return $this->meta !== null;
    }
}
