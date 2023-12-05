<?php

namespace CmsTool\Support\Validation\Symfony;

use Symfony\Component\Validator\Constraints\Image as SymfonyImage;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Image extends SymfonyImage
{
    /**
     * @var integer[]
     */
    public array $ignoreErrors = [];

    /**
     * @param integer[]|integer|null $ignoreErrors
     */
    public function __construct(
        array $options = null,
        array|int $ignoreErrors = null,
        int|string $maxSize = null,
        bool $binaryFormat = null,
        array $mimeTypes = null,
        int $filenameMaxLength = null,
        int $minWidth = null,
        int $maxWidth = null,
        int $maxHeight = null,
        int $minHeight = null,
        int|float $maxRatio = null,
        int|float $minRatio = null,
        int|float $minPixels = null,
        int|float $maxPixels = null,
        bool $allowSquare = null,
        bool $allowLandscape = null,
        bool $allowPortrait = null,
        bool $detectCorrupted = null,
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
        string $sizeNotDetectedMessage = null,
        string $maxWidthMessage = null,
        string $minWidthMessage = null,
        string $maxHeightMessage = null,
        string $minHeightMessage = null,
        string $minPixelsMessage = null,
        string $maxPixelsMessage = null,
        string $maxRatioMessage = null,
        string $minRatioMessage = null,
        string $allowSquareMessage = null,
        string $allowLandscapeMessage = null,
        string $allowPortraitMessage = null,
        string $corruptedMessage = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct(
            $options,
            $maxSize,
            $binaryFormat,
            $mimeTypes,
            $filenameMaxLength,
            $minWidth,
            $maxWidth,
            $maxHeight,
            $minHeight,
            $maxRatio,
            $minRatio,
            $minPixels,
            $maxPixels,
            $allowSquare,
            $allowLandscape,
            $allowPortrait,
            $detectCorrupted,
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
            $sizeNotDetectedMessage,
            $maxWidthMessage,
            $minWidthMessage,
            $maxHeightMessage,
            $minHeightMessage,
            $minPixelsMessage,
            $maxPixelsMessage,
            $maxRatioMessage,
            $minRatioMessage,
            $allowSquareMessage,
            $allowLandscapeMessage,
            $allowPortraitMessage,
            $corruptedMessage,
            $groups,
            $payload,
        );

        $ignoreErrors = $ignoreErrors ?? [UPLOAD_ERR_NO_FILE];

        $this->ignoreErrors = is_array($ignoreErrors)
            ? $ignoreErrors
            : [$ignoreErrors];
    }
}
