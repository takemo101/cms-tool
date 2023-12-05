<?php

namespace Takemo101\CmsTool\Infra\Storage;

use DI\Attribute\Inject;
use Takemo101\Chubby\Filesystem\PathHelper;

class LocalPublicStoragePath
{
    /**
     * constructor
     *
     * @param string $url
     * @param string $linkPath
     * @param string $storagePath
     * @param PathHelper $helper
     */
    public function __construct(
        #[Inject('config.storage.public.url')]
        private string $url,
        #[Inject('config.storage.public.link_path')]
        private string $linkPath,
        #[Inject('config.storage.public.storage_path')]
        private string $storagePath,
        private PathHelper $helper,
    ) {
        //
    }

    /**
     * Get public url
     *
     * @param string ...$paths
     * @return string
     */
    public function getUrl(string ...$paths): string
    {
        return $this->helper->join(
            $this->url,
            ...$paths,
        );
    }

    /**
     * Get public symlink path
     *
     * @return string
     */
    public function getLinkPath(): string
    {
        return $this->linkPath;
    }

    /**
     * Get storage path
     *
     * @param string ...$paths
     * @return string
     */
    public function getStoragePath(string ...$paths): string
    {
        return $this->helper->join(
            $this->storagePath,
            ...$paths,
        );
    }

    public function getRelativePath(string $path): string
    {
        return str_replace($this->storagePath, '', $path);
    }
}
