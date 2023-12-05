<?php

namespace Takemo101\CmsTool\UseCase\SiteMeta\Handler;

use Takemo101\CmsTool\Domain\SiteMeta\SiteMeta;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMetaRepository;
use Takemo101\CmsTool\UseCase\Shared\Exception\InstallSettingException;

class ChangeSiteNameHandler
{
    /**
     * constructor
     *
     * @param SiteMetaRepository $repository
     */
    public function __construct(
        private SiteMetaRepository $repository,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @param string $name
     * @return SiteMeta
     * @throws InstallSettingException
     */
    public function handle(string $name): SiteMeta
    {
        $meta = $this->repository->find();

        if (!$meta) {
            throw InstallSettingException::notExistsSetting();
        }

        $changedMeta = $meta->changeName($name);

        $this->repository->save($changedMeta);

        return $changedMeta;
    }
}
