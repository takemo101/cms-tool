<?php

namespace Takemo101\CmsTool\UseCase\SiteMeta\QueryService;

readonly class SiteSeoData
{
    /**
     * constructor
     *
     * @param string|null $title
     */
    public function __construct(
        public ?string $title,
        public ?string $description,
        public ?string $keywords,
        public ?string $favicon,
        public ?string $icon,
        public ?string $robots,
    ) {
        //
    }
}
