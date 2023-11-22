<?php

namespace Takemo101\CmsTool\Domain\Install;

use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiRepository;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMetaRepository;

class InstalledSpec
{
    /**
     * constructor
     *
     * @param MicroCmsApiRepository $microCmsApiRepository
     * @param SiteMetaRepository $siteMetaRepository
     * @param RootAdminRepository $rootAdminRepository
     */
    public function __construct(
        private MicroCmsApiRepository $microCmsApiRepository,
        private SiteMetaRepository $siteMetaRepository,
        private RootAdminRepository $rootAdminRepository,
    ) {
        //
    }

    /**
     * Is it installed?
     *
     * @return boolean
     */
    public function isSatisfiedBy(): bool
    {
        return $this->microCmsApiRepository->exists()
            && $this->siteMetaRepository->exists()
            && $this->rootAdminRepository->exists();
    }
}
