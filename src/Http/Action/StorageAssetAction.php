<?php

namespace Takemo101\CmsTool\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Http\Renderer\StaticRenderer;
use Takemo101\CmsTool\Infra\Storage\LocalPublicStoragePath;

class StorageAssetAction
{
    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     * @param LocalPublicStoragePath $storagePath
     */
    public function __construct(
        private LocalFilesystem $filesystem,
        private LocalPublicStoragePath $storagePath,
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
        $assetPath = $this->storagePath->getStoragePath($path);

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
