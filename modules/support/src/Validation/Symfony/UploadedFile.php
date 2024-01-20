<?php

namespace CmsTool\Support\Validation\Symfony;

use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;
use RuntimeException;

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

            // If the file size is too large,
            // There is an exception because the file cannot be moved
            try {
                /** @var string */
                $path = $psrUploadedFile->getStream()->getMetadata('uri');

                if ($this->test = !is_string($path)
                    || !is_uploaded_file($path)
                ) {
                    $path = $getTemporaryPath();
                    $psrUploadedFile->moveTo($path);
                }
            } catch (RuntimeException $e) {
                // If an exception occurs, it is considered that the file has not been uploaded.
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
}
