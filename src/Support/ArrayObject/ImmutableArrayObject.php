<?php

namespace Takemo101\CmsTool\Support\ArrayObject;

use Illuminate\Support\Arr;
use OutOfBoundsException;

/**
 * @extends ImmutableArrayObjectable<array-key,mixed>
 */
class ImmutableArrayObject extends ImmutableArrayObjectable
{
    private ArrayKeySearcher $searcher;

    /**
     * {@inheritDoc}
     */
    protected function __init(): void
    {
        $this->searcher = ArrayKeySearcher::createSnakeAndCamelCaseSearcher();
    }

    /**
     * Get the value of original
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed
    {
        return Arr::get($this->items, $name, $default);
    }

    /**
     * {@inheritDoc}
     *
     * @throws OutOfBoundsException
     */
    public function offsetGet($offset): mixed
    {
        // If the offset is an numeric, it is treated as an index.
        if (is_numeric($offset)) {
            return $this->items[$offset] ?? null;
        }

        if ($key = $this->searcher->search($offset, $this->items)) {
            return $this->items[$key] ?? throw new OutOfBoundsException('offset not found');
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        // If the offset is an numeric, it is treated as an index.
        if (is_numeric($offset)) {
            return array_key_exists($offset, $this->items);
        }

        return $this->searcher->search($offset, $this->items) !== false;
    }
}
