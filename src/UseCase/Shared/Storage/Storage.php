<?php

namespace Takemo101\CmsTool\UseCase\Shared\Storage;

use Psr\Http\Message\UploadedFileInterface;

interface Storage
{
    /**
     * Save the asset for the site settings
     *
     * @param UploadedFileInterface $file Uploaded file
     * @return string|null If you save, return an early dropping pass, and if you do not save, return NULL.
     */
    public function store(UploadedFileInterface $file): ?string;

    /**
     * Delete the asset for the site settings
     *
     * @param string $path Specify a relative path
     * @return boolean
     */
    public function delete(string $path): bool;
}
