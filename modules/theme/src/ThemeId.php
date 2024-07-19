<?php

namespace CmsTool\Theme;

use Stringable;

class ThemeId implements Stringable
{
    /**
     * constructor
     *
     * @param string $value
     */
    public function __construct(
        private readonly string $value,
    ) {
        assert($value !== '', 'The theme id cannot be empty.');
    }

    /**
     * Check if the id matches the specified id.
     *
     * @param self $id
     * @return bool
     */
    public function equals(self $id): bool
    {
        return $this->value() === $id->value();
    }

    /**
     * Get the id of the active theme.
     *
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Generate a new id.
     *
     * @return self
     */
    public static function generate(): self
    {
        return new self(uniqid());
    }
}
