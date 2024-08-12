<?php

namespace CmsTool\Support\Feed;

/**
 * Data class for article author information.
 *
 * @immutable
 */
readonly class FeedAuthor
{
    /**
     * constructor
     *
     * @param string $name
     * @param string|null $email
     */
    public function __construct(
        public string $name,
        public ?string $email = null,
    ) {
        //
    }
}
