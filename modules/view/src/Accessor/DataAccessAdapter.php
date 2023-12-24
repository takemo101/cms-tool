<?php

namespace CmsTool\View\Accessor;

use ArrayAccess;
use RuntimeException;

/**
 * @implements ArrayAccess<string,mixed>
 */
class DataAccessAdapter implements ArrayAccess
{
    /**
     * constructor
     *
     * @param DataAccessors $accessors
     */
    public function __construct(
        private DataAccessors $__accessors,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists(mixed $offset): bool
    {
        if (!is_string($offset)) {
            return false;
        }

        return $this->__accessors->has($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet(mixed $offset): mixed
    {
        if (!is_string($offset)) {
            return null;
        }

        return $this->__accessors->get($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new RuntimeException('This is a read-only array.');
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new RuntimeException('This is a read-only array.');
    }

    /**
     * @param string $offset
     * @return mixed
     */
    public function __get(string $offset)
    {
        return $this->offsetGet($offset);
    }

    /**
     * @param string $offset
     * @return boolean
     */
    public function __isset(string $offset): bool
    {
        return $this->offsetExists($offset);
    }
}
