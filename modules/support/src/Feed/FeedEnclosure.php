<?php

namespace CmsTool\Support\Feed;

/**
 * Data class for article attachments.
 *
 * @immutable
 */
readonly class FeedEnclosure
{
    /**
     * constructor
     *
     * @param string $url
     * @param integer $length
     * @param string $type
     */
    public function __construct(
        public string $url,
        public int $length,
        public string $type,
    ) {
        //
    }
}
