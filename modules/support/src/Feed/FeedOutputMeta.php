<?php

namespace CmsTool\Support\Feed;

/**
/**
 * Data class representing the meta information of the feed output by the feed generator.
 *
 * @immutable
 */
readonly class FeedOutputMeta
{
    /**
     * constructor
     *
     * @param string $charset
     * @param string $contentType
     * @param string $mimeType
     */
    public function __construct(
        public string $charset = 'UTF-8',
        public string $contentType = 'application/xml',
        public string $mimeType = 'application/rss+xml',
    ) {
        assert(
            empty($charset) === false,
            'The charset must not be empty.'
        );

        assert(
            empty($contentType) === false,
            'The content type must not be empty.'
        );

        assert(
            empty($mimeType) === false,
            'The MIME type must not be empty.'
        );
    }
}
