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
        assert(
            empty($taxonomy) === false,
            'The taxonomy endpoint must not be empty',
        );

        assert(
            empty($content) === false,
            'The content endpoint must not be empty',
        );
    }
}
