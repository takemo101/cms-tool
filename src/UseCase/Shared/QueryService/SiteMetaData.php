<?php

namespace Takemo101\CmsTool\UseCase\Shared\QueryService;

readonly class SiteMetaData
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
        public ?string $description = null,
    ) {
        //
    }
}
