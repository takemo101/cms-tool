<?php

namespace Takemo101\CmsTool\Http\Request\Admin;

use CmsTool\Support\Validation\Symfony\File;
use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\Validator\Constraints\Length;

readonly class ChangeSiteSeoRequest
{
    public function __construct(
        #[Length(max: 50)]
        public string $title,
        #[Length(max: 200)]
        public string $description,
        #[Length(max: 100)]
        public string $keywords,
        #[File(mimeTypes: ['image/png', 'image/jpeg'])]
        public ?UploadedFileInterface $favicon = null,
        #[File(mimeTypes: ['image/png', 'image/jpeg'])]
        public ?UploadedFileInterface $icon = null,
        #[Length(max: 50)]
        public string $robots = '',
    ) {
        //
    }

    /**
     * @return UploadedFileInterface|null
     */
    public function getFaviconOr(): ?UploadedFileInterface
    {
        return $this->favicon?->getError() == UPLOAD_ERR_NO_FILE
            ? null
            : $this->favicon;
    }

    /**
     * @return UploadedFileInterface|null
     */
    public function getIconOr(): ?UploadedFileInterface
    {
        return $this->icon?->getError() == UPLOAD_ERR_NO_FILE
            ? null
            : $this->icon;
    }
}
