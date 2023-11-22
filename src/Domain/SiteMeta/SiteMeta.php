<?php

namespace Takemo101\CmsTool\Domain\SiteMeta;


readonly class SiteMeta
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
        assert(!empty($name), 'name is empty');
        assert(!empty($title), 'title is empty');
    }
}
