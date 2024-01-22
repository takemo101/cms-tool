<?php

namespace Takemo101\CmsTool\Infra\Listener;

use Takemo101\Chubby\Event\Attribute\AsEventListener;
use Takemo101\CmsTool\Infra\Event\Uninstalled;
use Takemo101\CmsTool\Infra\Storage\Repository\RobotsTxtRepository;

#[AsEventListener(Uninstalled::class)]
class DeleteRobotsTxtListener
{
    /**
     * constructor
     *
     * @param RobotsTxtRepository $repository
     */
    public function __construct(
        private RobotsTxtRepository $repository,
    ) {
        //
    }

    /**
     * @return void
     */
    public function __invoke(): void
    {
        $this->repository->delete();
    }
}
