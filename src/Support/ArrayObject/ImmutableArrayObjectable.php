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
     * constructor
     *
     * @param array<TKey,TValue> $items
     */
    final public function __construct(
        protected readonly array $items = [],
    ) {
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
     * @param mixed $value
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
        return new ArrayIterator($this->items);
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
        return count($this->items);
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
            $this->items,
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
            fn($item) => $item instanceof Arrayable
                ? $item->toArray()
                : $item,
            $this->items,
        );
    }

    /**
     * Constructor of static method.
     *
     * @template TK as array-key
     * @template TV
     *
     * @param array<TK,TV> $items
     * @return static<TK,TV>
     */
    final public static function of(
        array $items = [],
    ): static {

        /** @var array<string,mixed> */
        $items = array_map(
            fn ($item) => is_array($item)
                ? static::of($item)
                : $item,
            $items,
        );

        return new static($items);
    }
}
