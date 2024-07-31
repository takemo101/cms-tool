<?php

namespace Takemo101\CmsTool\Preset\Shared\HeaderTitle;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * Data class to structure the header titles of the content.
 *
 * @immutable
 */
readonly class HeaderLayers implements Countable, IteratorAggregate
{
    /**
     * @var HeaderLayer[]
     */
    private array $layers;

    /**
     * constructor
     *
     * @param HeaderLayer ...$layers
     */
    public function __construct(
        HeaderLayer ...$layers,
    ) {
        $this->layers = $layers;
    }

    /**
     * Check if the layers are empty
     *
     * @return boolean
     */
    public function isEmpty(): bool
    {
        return empty($this->layers);
    }

    /**
     * Select only the layers with the specified range of levels and create a new instance
     *
     * @param integer $start
     * @param integer $end
     * @return self
     */
    public function select(
        int $start = 1,
        int $end = 6,
    ): self {
        $layers = [];

        foreach ($this->layers as $layer) {
            if ($layer->level->isWithinRange($start, $end)) {
                $layers[] = $layer->select($start, $end);
            }
        }

        return new self(...$layers);
    }

    /**
     * Add a layer and create a new instance
     *
     * @param HeaderLayer $layer
     * @return self
     */
    public function add(HeaderLayer $layer): self
    {
        $layers = $this->layers;

        $count = count($layers);

        if ($count === 0) {
            $layers[] = $layer;
        } else {

            $endIndex = $count - 1;

            $end = $layers[$endIndex];

            // If the level of the added layer is not the next level, create a new layer.
            if ($end->level->equals($layer->level)) {
                $layers[] = $layer;
            } else {
                $layers[$endIndex] = $end->add($layer);
            }
        }

        return new self(...$layers);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->layers);
    }

    /**
     * {@inheritDoc}
     *
     * @return Traversable<array-key,HeaderTitle>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->layers);
    }
}
