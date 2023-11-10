<?php

namespace Takemo101\CmsTool\Support;

use Takemo101\Chubby\Filesystem\PathHelper;

final class VendorPath
{
    /**
     * @var PathHelper
     */
    private PathHelper $helper;

    /**
     * constructor
     *
     * @param string $basePath
     * @param string $sourcePath
     * @param string $configPath
     * @param string $resourcePath
     */
    public function __construct(
        private string $basePath,
        private string $sourcePath = 'src',
        private string $configPath = 'config' . DIRECTORY_SEPARATOR . 'vendor',
        private string $resourcePath = 'resources',
    ) {
        $this->helper = new PathHelper();
    }

    /**
     * Path to base directory
     *
     * @param string|null $path
     * @return string
     */
    public function getBasePath(?string $path = null): string
    {
        return $path
            ? $this->helper->join($this->basePath, $path)
            : $this->basePath;
    }

    /**
     * Path to source directory
     *
     * @param string|null $path
     * @return string
     */
    public function getSourcePath(?string $path = null): string
    {
        $extendPath = $path
            ? [$this->sourcePath, $path]
            : [$this->sourcePath];

        return $this->getBasePath(
            $this->helper->join(...$extendPath),
        );
    }

    /**
     * Path to config directory
     *
     * @param string|null $path
     * @return string
     */
    public function getConfigPath(?string $path = null): string
    {
        $extendPath = $path
            ? [$this->configPath, $path]
            : [$this->configPath];

        return $this->getBasePath(
            $this->helper->join(...$extendPath),
        );
    }

    /**
     * Path to resource directory
     *
     * @param string|null $path
     * @return string
     */
    public function getResourcePath(?string $path = null): string
    {
        $extendPath = $path
            ? [$this->resourcePath, $path]
            : [$this->resourcePath];

        return $this->getBasePath(
            $this->helper->join(...$extendPath),
        );
    }
}
