<?php

namespace Takemo101\CmsTool\Support;

use Takemo101\Chubby\Filesystem\PathHelper;

readonly class VendorPath
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
        public string $basePath,
        public string $sourcePath = 'src',
        public string $configPath = 'config',
        public string $resourcePath = 'resources',
    ) {
        $this->helper = new PathHelper();
    }

    /**
     * Path to base directory
     *
     * @param string ...$paths
     * @return string
     */
    public function getBasePath(string ...$paths): string
    {
        return $this->helper->join($this->basePath, ...$paths);
    }

    /**
     * Path to source directory
     *
     * @param string ...$paths
     * @return string
     */
    public function getSourcePath(string ...$paths): string
    {
        return $this->getBasePath(
            $this->sourcePath,
            ...$paths,
        );
    }

    /**
     * Path to config directory
     *
     * @param string ...$paths
     * @return string
     */
    public function getConfigPath(string ...$paths): string
    {
        return $this->getBasePath(
            $this->configPath,
            ...$paths,
        );
    }

    /**
     * Path to resource directory
     *
     * @param string ...$paths
     * @return string
     */
    public function getResourcePath(string ...$paths): string
    {
        return $this->getBasePath(
            $this->resourcePath,
            ...$paths,
        );
    }
}
