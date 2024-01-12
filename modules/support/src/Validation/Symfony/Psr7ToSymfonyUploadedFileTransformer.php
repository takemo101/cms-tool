<?php

namespace CmsTool\Support\Validation\Symfony;

use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;

class Psr7ToSymfonyUploadedFileTransformer
{
    public function __invoke(UploadedFileInterface $psrUploadedFile): UploadedFile
    {
        return new UploadedFile(
            $psrUploadedFile,
            fn () => tempnam(
                sys_get_temp_dir(),
                uniqid('symfony', true),
            ) ?: throw new RuntimeException('Unable to create a temporary file'),
        );
    }
}
