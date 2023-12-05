<?php

namespace Takemo101\CmsTool\UseCase\SiteMeta\Handler;

use Takemo101\CmsTool\Domain\SiteMeta\SiteMeta;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMetaRepository;
use Takemo101\CmsTool\Domain\SiteMeta\SiteSeoImageTarget;
use Takemo101\CmsTool\UseCase\Shared\Exception\InstallSettingException;
use Takemo101\CmsTool\UseCase\Shared\Storage\SiteAssetStorage;
use Takemo101\CmsTool\UseCase\Shared\Storage\StorageHelper;

class CleanSiteSeoImageHandler
{
    /**
     * constructor
     *
     * @param SiteMetaRepository $repository
     */
    public function __construct(
        private SiteMetaRepository $repository,
        private SiteAssetStorage $storage,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @param SiteSeoImageTarget $target
     * @return SiteMeta
     * @throws InstallSettingException
     */
    public function handle(SiteSeoImageTarget $target): SiteMeta
    {
        $meta = $this->repository->find();

        if (!$meta) {
            throw InstallSettingException::notExistsSetting();
        }

        $helper = new StorageHelper($this->storage);

        $seo = $meta->seo;

        $changedMeta = $meta->cleanSeoImage($target);

        $this->repository->save($changedMeta);

        match ($target) {
            SiteSeoImageTarget::Favicon => $helper->deleteIfNotEmpty($seo->favicon),
            SiteSeoImageTarget::Icon => $helper->deleteIfNotEmpty($seo->icon),
        };

        return $changedMeta;
    }
}
