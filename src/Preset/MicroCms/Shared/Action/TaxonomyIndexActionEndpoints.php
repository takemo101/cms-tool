<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Shared\Action;

readonly class TaxonomyIndexActionEndpoints
{
    /**
     * constructor
     *
     * @param string $taxonomy
     * @param string $content
     */
    public function __construct(
        public string $taxonomy,
        public string $content,
    ) {
        //
    }
}
