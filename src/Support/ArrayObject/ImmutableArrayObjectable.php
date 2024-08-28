<?php

namespace Takemo101\CmsTool\Support\ArrayObject;

use Takemo101\Chubby\Contract\Arrayable;
use IteratorAggregate;
use ArrayAccess;
use ArrayIterator;
use Countable;
use JsonSerializable;
use Traversable;
use OutOfBoundsException;

/**
 * @template TKey as array-key
 * @template TValue
 *
 * @implements ArrayAccess<TKey,TValue>
 * @implements IteratorAggregate<TKey,TValue>
 * @implements Arrayable<TKey,TValue>
 */
abstract class ImmutableArrayObjectable implements IteratorAggregate, ArrayAccess, Countable, JsonSerializable, Arrayable
{
    /**
     * @var array<TKey,TValue>
     */
    protected readonly array $__items;

    /**
     * constructor
     *
     * @param array<TKey,TValue> $items
     */
    final public function __construct(
        array $items = [],
    ) {
        $this->__items = $items;

        $this->__init();
    }

    /**
     * Initialize method.
     *
     * @return void
     */
    protected function __init(): void
    {
        //
    }

    /**
     * @param string $name
     * @return mixed
     */
    final public function __get(string $name): mixed
    {
        return $this->offsetGet($name);
    }

    /**
     * @param string $name
     * @param TValue $value
     * @return never
     * @throws OutOfBoundsException
     */
    final public function __set(string $name, mixed $value): void
    {
        $this->offsetSet($name, $value);
    }

    /**
     * @param string $name
     * @return boolean
     */
    final public function __isset(string $name): bool
    {
        return $this->offsetExists($name);
    }

    /**
     * @param string $name
     * @return never
     * @throws OutOfBoundsException
     */
    final public function __unset(string $name): void
    {
        $this->offsetUnset($name);
    }

    /**
     * {@inheritDoc}
     *
     * @return Traversable<TKey,TValue>
     */
    final public function getIterator(): Traversable
    {
        return new ArrayIterator($this->__items);
    }

    /**
     * {@inheritDoc}
     *
     * @param TKey $offset
     * @return TValue Can return all value types.
     */
    abstract public function offsetGet($offset): mixed;

    /**
     * {@inheritDoc}
     *
     * @param TKey $offset
     * @param TValue $value
     * @return never
     * @throws OutOfBoundsException
     */
    final public function offsetSet(
        $offset,
        $value
    ): void {
        throw new OutOfBoundsException('Object is immutable');
    }

    /**
     * {@inheritDoc}
     *
     * @param TKey $offset
     * @return boolean
     */
    abstract public function offsetExists($offset): bool;

    /**
     * {@inheritDoc}
     *
     * @param TKey $offset
     * @return never
     * @throws OutOfBoundsException
     */
    final public function offsetUnset($offset): void
    {
        throw new OutOfBoundsException('Object is immutable');
    }

    /**
     * {@inheritDoc}
     */
    final public function count(): int
    {
        return count($this->__items);
    }

    /**
     * {@inheritDoc}
     */
    final public function jsonSerialize(): mixed
    {
        return array_map(
            function ($item) {
                if ($item instanceof JsonSerializable) {
                    return $item->jsonSerialize();
                }

                if ($item instanceof Arrayable) {
                    return $item->toArray();
                }

                return $item;
            },
            $this->__items,
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return array<TKey,TValue>
     */
    final public function toArray(): array
    {
        return array_map(
            function ($item) {
                if ($item instanceof Arrayable) {
                    /** @var TValue */
                    $data = $item->toArray();

                    return $data;
                }

                return $item;
            },
            $this->__items,
        );
    }

    /**
     * Constructor of static method.
     *
     * @param array<TKey,TValue> $items
     * @return static(static<TKey,TValue>)
     */
    final public static function of(
        array $items = [],
    ): static {

        $items = array_map(
            fn ($item) => is_array($item)
                ? static::of($item)
                : $item,
            $items,
        );

        return new static($items);
    }
}
