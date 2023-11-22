<?php

namespace Takemo101\CmsTool\UseCase\SiteMeta\Handler;

readonly class SaveSiteMetaCommand
{
    /**
     * constructor
     *
     * @param string $name
     * @param string $title
     * @param string|null $description
     */
    public function __construct(
        public string $name,
        public string $title,
        public ?string $description,
    ) {
        //
    }
}
