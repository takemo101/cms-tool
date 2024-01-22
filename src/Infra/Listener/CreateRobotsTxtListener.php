<?php

namespace Takemo101\CmsTool\Infra\Listener;

use Takemo101\Chubby\Event\Attribute\AsEventListener;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\CmsTool\Infra\Event\Installed;
use Takemo101\CmsTool\Infra\Storage\Repository\RobotsTxtRepository;
use Takemo101\CmsTool\Support\VendorPath;

#[AsEventListener(Installed::class)]
class CreateRobotsTxtListener
{
    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     * @param RobotsTxtRepository $repository
     * @param VendorPath $path
     */
    public function __construct(
        private LocalFilesystem $filesystem,
        private RobotsTxtRepository $repository,
        private VendorPath $path,
    ) {
        //
    }

    /**
     * @return void
     */
    public function __invoke(): void
    {
        $content = $this->filesystem->read(
            $this->path->getResourcePath('txt', 'robots.txt'),
        );

        $this->repository->save($content);
    }
}
