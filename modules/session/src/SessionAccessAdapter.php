<?php

namespace CmsTool\Session;

use Odan\Session\SessionInterface as Session;
use ArrayAccess;
use Iterator;
use Countable;

/**
 * @implements ArrayAccess<string,mixed>
 * @implements Iterator<string,mixed>
 */
class SessionAccessAdapter implements ArrayAccess, Iterator, Countable
{
    /** @var string */
    public const IgnoreKeyPrefix = '_';

    /**
     * @var int[]|string[]
     */
    private array $keys;

    /**
     * constructor
     *
     * @param Session $session The session instance
     */
    public function __construct(
        private Session $session,
    ) {
        $keys = array_keys($session->all());

        $this->keys = array_filter(
            $keys,
            fn ($key) => !str_starts_with($key, self::IgnoreKeyPrefix),
        );
    }

    /**
     * Set session instance.
     *
     * @param Session $session
     * @return void
     */
    public function setSession(Session $session): void
    {
        $this->session = $session;
        $this->keys = array_keys($this->session->all());
    }

    /**
     * Whether a offset exists.
     *
     * @param string $offset An offset to check for
     *
     * @return bool true on success or false on failure
     */
    public function offsetExists($offset): bool
    {
        return $this->session->has($offset);
    }

    /**
     * Offset to retrieve.
     *
     * @param string $offset The offset to retrieve
     *
     * @return mixed Can return all value types
     */
    public function offsetGet($offset): mixed
    {
        return $this->session->get($offset);
    }

    /**
     * Offset to set.
     *
     * @param string $offset The offset to assign the value to
     * @param mixed $value  The value to set
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->session->set($offset, $value);
        $this->keys[] = $offset;
    }

    /**
     * Offset to unset.
     *
     * @param string $offset The offset to unset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        $this->session->delete($offset);

        unset($this->keys[$offset]);
    }

    /**
     * Count elements of an object.
     *
     * @return int The custom count as an integer.
     */
    public function count(): int
    {
        return count($this->keys);
    }

    /**
     * Return the current element.
     *
     * @return mixed Can return any type.
     */
    public function current(): mixed
    {
        /** @var string */
        $key = current($this->keys);

        return $this->session->get($key);
    }

    /**
     * Move forward to next element.
     *
     * @return void
     */
    public function next(): void
    {
        next($this->keys);
    }

    /**
     * Return the key of the current element.
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key(): mixed
    {
        return current($this->keys);
    }

    /**
     * Checks if current position is valid.
     *
     * @return bool The return value will be casted to boolean and then evaluated.
     */
    public function valid(): bool
    {
        return key($this->keys) !== null;
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @return void
     */
    public function rewind(): void
    {
        reset($this->keys);
    }
}
