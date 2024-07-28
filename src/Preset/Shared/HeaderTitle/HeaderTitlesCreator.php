<?php

namespace Takemo101\CmsTool\Preset\Shared\HeaderTitle;

use DOMDocument;
use DOMXPath;
use DOMElement;
use RuntimeException;

/**
 * Create HeaderTitles by parsing HTML string and extracting H tags.
 */
class HeaderTitlesCreator
{
    /**
     * Prefix for encoding XML in UTF-8.
     *
     * @var string
     */
    public const EncodingPrefix = '<?xml encoding="utf-8">';

    /**
     * constructor
     */
    public function __construct()
    {
        // If DOMDocument is not available, throw an exception
        if (!class_exists(DOMDocument::class)) {
            throw new RuntimeException('DOMDocument is not available');
        }
    }

    /**
     * Create header titles from HTML content
     *
     * @param string $content HTML content
     * @return HeaderTitles
     */
    public function create(string $content): HeaderTitles
    {
        $dom = new DOMDocument();

        // Prevent errors when there are duplicate ID attributes in h tags
        @$dom->loadHTML(
            self::EncodingPrefix . $content,
        );

        $xpath = new DOMXPath($dom);

        // Get elements from h1 to h6 using xpath
        $nodeList = $xpath->query(
            implode(
                '|',
                array_map(fn (int $level) => "//h{$level}", HeaderTitle::LevelRange),
            ),
        );

        if ($nodeList === false) {
            return new HeaderTitles();
        }

        $nodes = iterator_to_array($nodeList);

        /**
         * @var DOMElement[]
         */
        $elements = array_filter(
            $nodes,
            fn ($node) => $node instanceof DOMElement,
        );

        return HeaderTitles::fromHTagDOMElements(...$elements);
    }
}
