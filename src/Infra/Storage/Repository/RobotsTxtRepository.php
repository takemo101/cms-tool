<?php

namespace Takemo101\CmsTool\Infra\Storage\Repository;

use DI\Attribute\Inject;
use Takemo101\Chubby\Filesystem\LocalFilesystem;

class RobotsTxtRepository
{
    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     * @param string $path
     */
    public function __construct(
        private LocalFilesystem $filesystem,
        #[Inject('config.system.robots_txt.path')]
        private string $path = 'robots.txt',
    ) {
    }

    /**
     * Get robots.txt content
     *
     * @return string|null
     */
    public function get(): ?string
    {
        return $this->filesystem->read($this->path);
    }

    /**
     * Save robots.txt content
     *
     * @param string $content
     * @return void
     */
    public function save(string $content): void
    {
        $this->filesystem->write($this->path, $content);
    }

    /**
     * Delete robots.txt
     *
     * @return void
     */
    public function delete(): void
    {
        if ($this->filesystem->exists($this->path)) {
            $this->filesystem->delete($this->path);
        }
    }
}
