<?php

namespace CmsTool\Support\Feed;

/**
 * Data class for feed output.
 *
 * @immutable
 */
readonly class FeedOutput
{
    /**
     * constructor
     *
     * @param string $output
     * @param string $charset
     * @param string $contentType
     */
    public function __construct(
        public string $output,
        public string $charset = 'UTF-8',
        public string $contentType = 'application/xml',
    ) {
        assert(
            empty($output) === false,
            'The output must not be empty.'
        );

        assert(
            empty($charset) === false,
            'The charset must not be empty.'
        );

        assert(
            empty($contentType) === false,
            'The content type must not be empty.'
        );
    }
}
