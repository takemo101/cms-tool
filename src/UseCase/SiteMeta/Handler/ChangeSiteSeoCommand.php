<?php

namespace Takemo101\CmsTool\UseCase\SiteMeta\Handler;

use Psr\Http\Message\UploadedFileInterface;

readonly class ChangeSiteSeoCommand
{
    /**
     * constructor
     *
     * @param string|null $title
     * @param string|null $description
     * @param string|null $keywords
     * @param UploadedFileInterface|null $favicon
     * @param UploadedFileInterface|null $icon
     * @param string|null $robots
     */
    public function __construct(
        public ?string $title,
        public ?string $description,
        public ?string $keywords,
        public ?UploadedFileInterface $favicon,
        public ?UploadedFileInterface $icon,
        public ?string $robots,
    ) {
        //
    }
}
