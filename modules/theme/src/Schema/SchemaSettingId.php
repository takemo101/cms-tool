<?php

namespace CmsTool\Theme\Schema;

use Stringable;

/**
 * Schema setting ID
 * The ID is used to reference the data entered based on the schema.
 *
 * @immutable
 */
class SchemaSettingId implements Stringable
{
    /**
     * Regular expression for the schema setting id.
     *
     * @var string
     */
    public const Regex = '/^[a-z0-9_]+$/i';

    /**
     * constructor
     *
     * @param string $value
     */
    public function __construct(
        private readonly string $value,
    ) {
        assert(
            empty($value) === false,
            'The schema setting id cannot be empty.',
        );

        assert(
            preg_match(self::Regex, $value),
            'The schema setting id must only contain letters, numbers, and underscores.',
        );
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
}
