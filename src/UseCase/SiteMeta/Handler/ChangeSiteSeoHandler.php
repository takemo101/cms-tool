<?php

namespace Takemo101\CmsTool\UseCase\SiteMeta\Handler;

use Psr\Http\Message\UploadedFileInterface;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMeta;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMetaRepository;
use Takemo101\CmsTool\Domain\SiteMeta\SiteSeo;
use Takemo101\CmsTool\UseCase\Shared\Exception\InstallSettingException;
use Takemo101\CmsTool\UseCase\Shared\Storage\SiteAssetStorage;
use Takemo101\CmsTool\UseCase\Shared\Storage\StorageHelper;

class ChangeSiteSeoHandler
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
     * @param ChangeSiteSeoCommand $command
     * @return SiteMeta
     * @throws InstallSettingException
     */
    public function handle(ChangeSiteSeoCommand $command): SiteMeta
    {
        $meta = $this->repository->find();

        if (!$meta) {
            throw InstallSettingException::notExistsSetting();
        }

        $helper = new StorageHelper($this->storage);

        $seo = $meta->seo;

        $changedMeta = $meta->changeSeo(
            new SiteSeo(
                title: $command->title,
                description: $command->description,
                keywords: $command->keywords,
                favicon: $helper->storeOr(
                    $command->favicon,
                    $seo->favicon,
                ),
                icon: $helper->storeOr(
                    $command->icon,
                    $seo->icon,
                ),
                robots: $command->robots,
            )
        );

        $this->repository->save($changedMeta);

        $helper->deleteOriginalIfDiff(
            $changedMeta->seo->favicon,
            $seo->favicon,
        );

        $helper->deleteOriginalIfDiff(
            $changedMeta->seo->icon,
            $seo->icon,
        );

        return $changedMeta;
    }
}
