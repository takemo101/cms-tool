<?php

namespace CmsTool\Support\Feed;

use DateTimeInterface;

/**
 * Data class representing the overall information of the feed.
 *
 * @immutable
 */
readonly class Feed
{
    /**
     * @var string
     */
    public string $copyright;

    /**
     * constructor
     *
     * @param string $title
     * @param string $description
     * @param string $link
     * @param DateTimeInterface $updated lastBuildDate
     * @param string|null $copyright
     * @param string $language
     * @param FeedItems $items
     */
    public function __construct(
        public string $title,
        public string $description,
        public string $link,
        public DateTimeInterface $updated,
        ?string $copyright = null,
        public string $language = 'ja',
        public FeedItems $items = new FeedItems(),
    ) {
        assert(
            empty($title) === false,
            'Title must not be empty.',
        );

        assert(
            empty($link) === false,
            'Link must not be empty.',
        );

        assert(
            empty($language) === false,
            'Language must not be empty.',
        );

        $this->copyright = $copyright ?? $title;
    }
}
