<?php

namespace CmsTool\Support\Feed;

/**
 * Data class for the list of feed items.
 *
 * @immutable
 */
readonly class FeedItems
{
    /**
     * @var FeedItem[]
     */
    public array $items;

    /**
     * constructor
     *
     * @param FeedItem ...$items
     */
    public function __construct(
        FeedItem ...$items,
    ) {
        assert(
            $this->ensureGuidIsUnique(...$items),
            'GUID must be unique in feed items.',
        );

        $this->items = $items;
    }

    /**
     * Validates that the GUIDs of feed items are unique.
     *
     * @param FeedItem ...$items
     * @return boolean
     */
    private function ensureGuidIsUnique(FeedItem ...$items): bool
    {
        $guids = [];

        foreach ($items as $item) {
            if (in_array($item->guid, $guids, true)) {
                return false;
            }

            $guids[] = $item->guid;
        }

        return true;
    }
}
