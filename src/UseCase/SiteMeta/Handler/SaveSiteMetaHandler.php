<?php

namespace Takemo101\CmsTool\UseCase\SiteMeta\Handler;

use Takemo101\CmsTool\Domain\SiteMeta\SiteMeta;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMetaRepository;

readonly class SaveSiteMetaHandler
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
     * @param SaveSiteMetaCommand $command
     * @return void
     */
    public function handle(SaveSiteMetaCommand $command): void
    {
        $meta = new SiteMeta(
            name: $command->name,
            title: $command->title,
            description: $command->description,
        );

        $this->repository->save($meta);
    }
}
