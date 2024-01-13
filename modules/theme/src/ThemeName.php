<?php

namespace CmsTool\Theme;

use Stringable;

class ThemeName implements Stringable
{
    public const CopySuffix = ' (Copy)';

    /**
     * constructor
     *
     * @param string $value
     */
    public function __construct(
        private string $value,
    ) {
        assert($value !== '', 'The theme name cannot be empty.');
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
     * Copy the theme name.
     *
     * @return self
     */
    public function copy(): self
    {
        return new self($this->value() . self::CopySuffix);
    }
}
