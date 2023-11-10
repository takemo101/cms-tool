<?php

namespace Takemo101\CmsTool\Action;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Filesystem\PathHelper;
use Takemo101\Chubby\Http\Renderer\StaticRenderer;
use Takemo101\CmsTool\Support\VendorPath;

final class VendorAssetAction
{
    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     * @param PathHelper $pathHelper
     * @param VendorPath $vendorPath
     */
    public function __construct(
        private LocalFilesystem $filesystem,
        private PathHelper $pathHelper,
        private VendorPath $vendorPath,
    ) {
        //
    }

    /**
     * Hello to you
     *
     * @param string $path
     * @return StaticRenderer
     */
    public function __invoke(ServerRequestInterface $request, string $path): StaticRenderer
    {
        $assetPath = $this->pathHelper->join(
            $this->vendorPath->getResourcePath(
                'assets',
            ),
            $path,
        );

        if (
            !$this->filesystem->exists($assetPath)
            || !$this->filesystem->isFile($assetPath)
        ) {
            throw new HttpNotFoundException($request, 'Asset not found.');
        }

        return StaticRenderer::fromPath($assetPath)
            ->enableAutoEtag();
    }
}
