<?php

namespace CmsTool\Support\Feed;

/**
 * Data class for a list of article category names.
 *
 * @immutable
 */
readonly class FeedCategories
{
    /**
     * @var string[]
     */
    public array $categories;

    /**
     * constructor
     *
     * @param string ...$categories
     */
    public function __construct(
        string ...$categories,
    ) {
        // Remove duplicate categories
        $this->categories = array_unique($categories);
    }
}
