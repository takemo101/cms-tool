<?php

namespace CmsTool\Support\Validation\Symfony;

use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Psr7UploadedFile is a wrapper for SymfonyUploadedFile
 *
 * @reference: https://github.com/symfony/psr-http-message-bridge
 */
class UploadedFile extends SymfonyUploadedFile
{
    private bool $test = false;

    /**
     * constructor
     *
     * @param UploadedFileInterface $psrUploadedFile
     * @param callable():string $getTemporaryPath
     */
    public function __construct(
        private readonly UploadedFileInterface $psrUploadedFile,
        callable $getTemporaryPath,
    ) {
        $error = $psrUploadedFile->getError();
        $path = '';

        if (\UPLOAD_ERR_NO_FILE !== $error) {
            /** @var string */
            $path = $psrUploadedFile->getStream()->getMetadata('uri') ?? '';

            if (
                $this->test = !is_string($path)
                || !is_uploaded_file($path)
            ) {
                $path = $getTemporaryPath();
                $psrUploadedFile->moveTo($path);
            }
        }

        parent::__construct(
            $path,
            (string) $psrUploadedFile->getClientFilename(),
            $psrUploadedFile->getClientMediaType(),
            $psrUploadedFile->getError(),
            $this->test
        );
    }

    /**
     * Moves the file to a new location.
     *
     * @throws FileException if, for any reason, the file could not have been moved
     */
    public function move(string $directory, string $name = null): File
    {
        if (!$this->isValid() || $this->test) {
            return parent::move($directory, $name);
        }

        $target = $this->getTargetFile($directory, $name);

        try {
            $this->psrUploadedFile->moveTo((string) $target);
        } catch (\RuntimeException $e) {
            throw new FileException(sprintf('Could not move the file "%s" to "%s" (%s).', $this->getPathname(), $target, $e->getMessage()), 0, $e);
        }

        @chmod($target, 0666 & ~umask());

        return $target;
    }
}
