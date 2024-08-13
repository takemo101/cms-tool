<?php

namespace Takemo101\CmsTool\Support\Htmlable;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;
use CmsTool\View\Contract\Htmlable;

/**
 * @implements IteratorAggregate<array-key,Htmlable>
 */
abstract class AbstractHtmlableCollection implements IteratorAggregate, Countable, Htmlable
{
    /**
     * @var Htmlable[]
     */
    private array $htmls = [];

    /**
     * constructor
     *
     * @param Htmlable ...$htmls
     */
    public function __construct(Htmlable ...$htmls)
    {
        $this->htmls = $htmls;
    }

    /**
     * Add html.
     *
     * @param Htmlable ...$htmls
     * @return self
     */
    public function add(Htmlable ...$htmls): self
    {
        $this->htmls = [
            ...$this->htmls,
            ...$htmls,
        ];

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return Traversable<array-key,Htmlable>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->htmls);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->htmls);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        /** @var string[] */
        $htmls = [];

        foreach ($this as $html) {
            $htmls[] = $html->__toString();
        }

        return implode('', $htmls);
    }
}
