<?php

namespace Takemo101\CmsTool\Support\Shared;

use Illuminate\Support\Arr;
use Takemo101\Chubby\Contract\Arrayable;
use ArrayObject;
use OutOfBoundsException;

/**
 * @extends ArrayObject<string,mixed>
 * @implements Arrayable<string,mixed>
 */
class ImmutableArrayObject extends ArrayObject implements Arrayable
{
    /**
     * constructor
     *
     * @param array<string,mixed> $items
     */
    final protected function __construct(
        array $items = [],
    ) {
        parent::__construct(
            $items,
            ArrayObject::STD_PROP_LIST | ArrayObject::ARRAY_AS_PROPS,
        );
    }

    /**
     * Get the value of original
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this, $key, $default);
    }

    /**
     * Since it is immutable, set is prohibited
     *
     * @param string $key
     * @param mixed $value
     * @return void
     * @throws OutOfBoundsException
     */
    public function __set(string $key, mixed $value): void
    {
        $this->offsetSet($key, $value);
    }

    /**
     * Since it is immutable, it is prohibited to unset
     *
     * @param string $key
     * @return void
     * @throws OutOfBoundsException
     */
    public function __unset(string $key): void
    {
        $this->offsetUnset($key);
    }

    /**
     * Implementation of Arrayaccess
     * Because it is immutable, set is prohibited
     *
     * @param mixed $key
     * @param mixed $value
     * @return void
     * @throws OutOfBoundsException
     */
    public function offsetSet(mixed $key, mixed $value): void
    {
        throw new OutOfBoundsException(
            sprintf('Cannot set value in %s', static::class),
        );
    }

    /**
     * Implementation of Arrayaccess
     * Because it is immutable
     *
     * @param mixed $key
     * @return void
     * @throws OutOfBoundsException
     */
    public function offsetUnset(mixed $key): void
    {
        throw new OutOfBoundsException(
            sprintf('Cannot unset value in %s', static::class),
        );
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        /** @var array<string,mixed> */
        $result = array_map(
            fn ($item) => $item instanceof Arrayable
                ? $item->toArray()
                : $item,
            $this->getArrayCopy(),
        );

        return $result;
    }

    /**
     * コンストラクタのエイリアス
     *
     * @param array<string,mixed> $array
     * @return static
     */
    public static function of(
        array $array = [],
    ): static {

        /** @var array<string,mixed> */
        $items = array_map(
            fn ($item) => is_array($item)
                ? static::of($item)
                : $item,
            $array,
        );

        return new static($items);
    }
}
