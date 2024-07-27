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
        $dom->loadHTML($content);

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
