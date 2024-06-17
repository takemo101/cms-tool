<?php

namespace Takemo101\CmsTool\Domain\SiteMeta;

readonly class SiteSeo
{
    /**
     * constructor
     *
     * @param string|null $title
     * @param string|null $description
     * @param string|null $keywords
     * @param string|null $favicon
     * @param string|null $icon
     * @param string|null $robots
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $keywords = null,
        public ?string $favicon = null,
        public ?string $icon = null,
        public ?string $robots = null,
    ) {
        //
    }

    /**
     * Clean image
     *
     * @param SiteSeoImageTarget $target
     * @return self
     */
    public function cleanImage(SiteSeoImageTarget $target): self
    {
        return new self(
            title: $this->title,
            description: $this->description,
            keywords: $this->keywords,
            favicon: $target == SiteSeoImageTarget::Favicon
                ? null
                : $this->favicon,
            icon: $target == SiteSeoImageTarget::Icon
                ? null
                : $this->icon,
            robots: $this->robots,
        );
    }
}
