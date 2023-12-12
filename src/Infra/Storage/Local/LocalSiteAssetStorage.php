<?php

namespace Takemo101\CmsTool\Infra\Storage\Local;

use Psr\Http\Message\UploadedFileInterface;
use SplFileObject;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\CmsTool\Domain\Shared\IdCreator;
use Takemo101\CmsTool\Infra\Storage\LocalPublicStoragePath;
use Takemo101\CmsTool\UseCase\Shared\Storage\SiteAssetStorage;

class LocalSiteAssetStorage implements SiteAssetStorage
{
    public const BasePath = 'site';

    /**
     * constructor
     *
     * @param LocalPublicStoragePath $path
     * @param LocalFilesystem $filesystem
     */
    public function __construct(
        private LocalPublicStoragePath $path,
        private LocalFilesystem $filesystem,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function store(UploadedFileInterface $file): ?string
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            return null;
        }

        $this->createDirectoryIfNotExists();

        $info = pathinfo($file->getClientFilename());

        $extension = is_array($info)
            ? $info['extension'] ?? null
            : null;

        $path = $this->path->getStoragePath(
            self::BasePath,
            IdCreator::random(32)->__toString() . (
                $extension
                ? '.' . $extension
                : ''
            ),
        );

        $file->moveTo($path);

        return $this->path->getRelativePath($path);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $path): bool
    {
        return $this->filesystem->delete(
            $this->path->getStoragePath(
                $path,
            )
        );
    }

    public function createDirectoryIfNotExists(): void
    {
        $directory = $this->path->getStoragePath(
            self::BasePath,
        );

        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->makeDirectory($directory);
        }
    }
}
