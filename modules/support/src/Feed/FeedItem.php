<?php

namespace CmsTool\Support\Feed;

use DateTimeInterface;

/**
 * Data class representing each item in a feed.
 *
 * @immutable
 */
readonly class FeedItem
{
    /**
     * @var string
     */
    public string $guid;

    /**
     * constructor
     *
     * @param string $title
     * @param DateTimeInterface $published pubDate
     * @param string $content
     * @param string $link
     * @param string|null $guid
     * @param FeedEnclosure|null $enclosure
     * @param FeedAuthor|null $author
     * @param FeedCategories $categories
     */
    public function __construct(
        public string $title,
        public DateTimeInterface $published,
        public string $content,
        public string $link,
        ?string $guid = null,
        public ?FeedEnclosure $enclosure = null,
        public ?FeedAuthor $author = null,
        public FeedCategories $categories = new FeedCategories(),
    ) {
        assert(
            empty($this->title) === false,
            'Title must not be empty.',
        );

        assert(
            empty($this->link) === false,
            'Link must not be empty.',
        );

        // Use the link as the GUID if it is not specified
        $this->guid = $guid ?? $link;

        assert(
            empty($this->guid) === false,
            'GUID must not be empty.',
        );
    }

    /**
     * Determines if the GUID is a permalink.
     *
     * @return bool
     */
    public function isGuidPermalink(): bool
    {
        return str_starts_with($this->guid, 'http://') ||
            str_starts_with($this->guid, 'https://');
    }
}
