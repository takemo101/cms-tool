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
        private string $value,
    ) {
        assert($value !== '', 'The theme id cannot be empty.');
    }

    /**
     * Set the id of the active theme.
     *
     * @param string $value
     * @return void
     */
    protected function set(string $value): void
    {
        $this->value = $value;
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
     * Create an instance from the specified id.
     *
     * @param string $value
     * @return self
     */
    public static function fromThemeId(ThemeId $id): self
    {
        return new static($id->value());
    }
}
