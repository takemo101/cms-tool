<?php

namespace CmsTool\Support\Validation\Symfony;

use Symfony\Component\Validator\Constraints\File as SymfonyFile;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class File extends SymfonyFile
{
    /**
     * @var integer[]
     */
    public array $ignoreErrors = [];

    /**
     * {@inheritdoc}
     * @param integer[]|integer|null $ignoreErrors
     * @param string[]|string $mimeTypes
     * @param array<string,mixed>|null $options
     */
    public function __construct(
        array $options = null,
        array|int $ignoreErrors = null,
        int|string $maxSize = null,
        bool $binaryFormat = null,
        array|string $mimeTypes = null,
        int $filenameMaxLength = null,
        string $notFoundMessage = null,
        string $notReadableMessage = null,
        string $maxSizeMessage = null,
        string $mimeTypesMessage = null,
        string $disallowEmptyMessage = null,
        string $filenameTooLongMessage = null,
        string $uploadIniSizeErrorMessage = null,
        string $uploadFormSizeErrorMessage = null,
        string $uploadPartialErrorMessage = null,
        string $uploadNoFileErrorMessage = null,
        string $uploadNoTmpDirErrorMessage = null,
        string $uploadCantWriteErrorMessage = null,
        string $uploadExtensionErrorMessage = null,
        string $uploadErrorMessage = null,
        array $groups = null,
        mixed $payload = null,
        array|string $extensions = null,
        string $extensionsMessage = null,
    ) {
        parent::__construct(
            $options,
            $maxSize,
            $binaryFormat,
            $mimeTypes,
            $filenameMaxLength,
            $notFoundMessage,
            $notReadableMessage,
            $maxSizeMessage,
            $mimeTypesMessage,
            $disallowEmptyMessage,
            $filenameTooLongMessage,
            $uploadIniSizeErrorMessage,
            $uploadFormSizeErrorMessage,
            $uploadPartialErrorMessage,
            $uploadNoFileErrorMessage,
            $uploadNoTmpDirErrorMessage,
            $uploadCantWriteErrorMessage,
            $uploadExtensionErrorMessage,
            $uploadErrorMessage,
            $groups,
            $payload,
            $extensions,
            $extensionsMessage,
        );

        $ignoreErrors = $ignoreErrors ?? [UPLOAD_ERR_NO_FILE];

        $this->ignoreErrors = is_array($ignoreErrors)
            ? $ignoreErrors
            : [$ignoreErrors];
    }
}
