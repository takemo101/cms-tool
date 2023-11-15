<?php

namespace Takemo101\CmsTool\Support;

use Takemo101\Chubby\Filesystem\PathHelper;
use Takemo101\Chubby\Support\ApplicationPath;

final class SystemPath
{
    /**
     * @var PathHelper
     */
    private PathHelper $helper;

    /**
     * constructor
     *
     * @param ApplicationPath $path
     * @param string $publicPath
     * @param string $themePath
     */
    public function __construct(
        private ApplicationPath $path,
        private string $publicPath = 'public',
        private string $themePath = 'themes',
    ) {
        $this->helper = new PathHelper();
    }

    /**
     * Path to public directory
     *
     * @param string|null $path
     * @return string
     */
    public function getPublicPath(?string $path = null): string
    {
        $extendPath = $path
            ? [$this->publicPath, $path]
            : [$this->publicPath];

        return $this->path->getBasePath(
            $this->helper->join(...$extendPath),
        );
    }

    /**
     * Path to theme directory
     *
     * @param string|null $path
     * @return string
     */
    public function getThemePath(?string $path = null): string
    {
        $extendPath = $path
            ? [$this->themePath, $path]
            : [$this->themePath];

        return $this->path->getBasePath(
            $this->helper->join(...$extendPath),
        );
    }
}
