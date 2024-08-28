<?php

namespace Takemo101\CmsTool\UseCase\Shared\Storage;

use Psr\Http\Message\UploadedFileInterface;

class StorageHelper
{
    /**
     * constructor
     *
     * @param Storage $storage
     */
    public function __construct(
        private Storage $storage,
    ) {
        //
    }

    /**
     * @param UploadedFileInterface|null $file
     * @param string|null $originalPath
     * @return string|null
     */
    public function storeOr(
        ?UploadedFileInterface $file,
        ?string $originalPath = null,
    ): ?string {
        if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
            return $originalPath;
        }

        return $this->storage->store($file) ?? $originalPath;
    }

    /**
     * If the original file is different from the saved file, delete it.
     *
     * @param string $storedPath Already saved path
     * @param string $originalPath
     * @return void
     */
    public function deleteOriginalIfDiff(
        ?string $storedPath,
        ?string $originalPath,
    ): void {
        if (!$storedPath || !$originalPath) {
            return;
        }

        if ($storedPath !== $originalPath) {
            $this->storage->delete($originalPath);
        }
    }

    /**
     * If the saved file is not empty, delete it.
     *
     * @param string $storedPath Already saved path
     * @return void
     */
    public function deleteIfNotEmpty(
        ?string $storedPath,
    ): void {
        if (!$storedPath) {
            return;
        }

        $this->storage->delete($storedPath);
    }
}
