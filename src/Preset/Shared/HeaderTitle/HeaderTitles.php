<?php

namespace Takemo101\CmsTool\Preset\Shared\HeaderTitle;

use ArrayIterator;
use Countable;
use DOMElement;
use IteratorAggregate;
use Traversable;

/**
 * Data class to structure the header titles of the content.
 *
 * @immutable
 */
readonly class HeaderTitles implements Countable, IteratorAggregate
{
    /**
     * @var HeaderTitle[]
     */
    private array $titles;

    /**
     * constructor
     *
     * @param HeaderTitle ...$titles
     */
    public function __construct(
        HeaderTitle ...$titles,
    ) {
        $this->titles = $titles;
    }

    /**
     * Check if the titles are empty
     *
     * @return boolean
     */
    public function isEmpty(): bool
    {
        return empty($this->titles);
    }

    /**
     * Select only the titles with the specified range of levels and create a new instance
     *
     * @param integer $start
     * @param integer $end
     * @return self
     */
    public function select(
        int $start = 1,
        int $end = 6,
    ): self {
        $titles = [];
        foreach ($this->titles as $title) {
            if ($title->level >= $start && $title->level <= $end) {
                $titles[] = $title;
            }
        }

        return new self(...$titles);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->titles);
    }

    /**
     * {@inheritDoc}
     *
     * @return Traversable<array-key,HeaderTitle>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->titles);
    }

    /**
     * Create an instance from DOMElements
     *
     * @param DOMElement ...$elements
     * @return HeaderTitles
     */
    public static function fromHTagDOMElements(DOMElement ...$elements): self
    {
        $titles = array_map(
            fn (DOMElement $element) => HeaderTitle::fromHTagDOMElement($element),
            $elements,
        );

        return new self(...$titles);
    }
}
