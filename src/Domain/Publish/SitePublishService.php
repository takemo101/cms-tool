<?php

namespace Takemo101\CmsTool\Domain\Publish;

class SitePublishService
{
    /**
     * constructor
     *
     * @param SitePublishRepository $repository
     */
    public function __construct(
        private SitePublishRepository $repository,
    ) {
        //
    }

    /**
     * Make the site externally published
     *
     * @return void
     */
    public function publish(): void
    {
        $this->repository->save(true);
    }

    /**
     * Make the site externally unpublished
     *
     * @return void
     */
    public function unpublish(): void
    {
        $this->repository->save(false);
    }
}
