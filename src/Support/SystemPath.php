<?php

namespace Takemo101\CmsTool\Support;

use Takemo101\Chubby\Support\ApplicationPath;

readonly class SystemPath
{
    /**
     * constructor
     *
     * @param ApplicationPath $path
     * @param string $publicPath
     * @param string $themePath
     */
    public function __construct(
        private ApplicationPath $path,
        public string $publicPath = 'public',
        public string $themePath = 'themes',
    ) {
        //
    }

    /**
     * Path to public directory
     *
     * @param string|null $path
     * @return string
     */
    public function getPublicPath(string ...$paths): string
    {
        return $this->path->getBasePath(
            $this->publicPath,
            ...$paths,
        );
    }

    /**
     * Path to theme directory
     *
     * @param string|null $path
     * @return string
     */
    public function getThemePath(string ...$paths): string
    {
        return $this->path->getBasePath(
            $this->themePath,
            ...$paths,
        );
    }
}
