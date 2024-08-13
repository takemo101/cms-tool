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
     * @param integer|null $length
     * @param string|null $type
     */
    public function __construct(
        public string $url,
        public ?int $length = null,
        public ?string $type = null,
    ) {
        //
    }
}
