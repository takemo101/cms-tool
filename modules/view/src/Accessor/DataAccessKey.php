<?php

namespace CmsTool\View\Accessor;

use Stringable;

/**
 * Key for accessing data
 */
class DataAccessKey implements Stringable
{
    /** @var string */
    public const Placeholder = '*';

    /** @var string */
    public readonly string $value;

    /**
     * constructor
     *
     * @param string $value
     */
    public function __construct(
        string $value,
    ) {
        $value = trim($value);

        assert(
            (bool) preg_match('/^[a-zA-Z0-9_' . self::Placeholder . ']+$/', $value),
            "Invalid key: {$value}",
        );

        $this->value = $value;
    }

    /**
     * Get the play holder match pattern
     *
     * @return string
     */
    public function getPlaceholderPattern(): string
    {
        return '/^' . str_replace('*', '(.+)', $this->value) . '$/';
    }

    /**
     * Extract the place holder from the key
     * If the key does not match, return False
     *
     * @param string $key
     * @return string[]|false
     */
    public function extractArguments(string $key): array|false
    {
        if ($this->value === $key) {
            return [];
        }

        if (
            preg_match(
                $this->getPlaceholderPattern(),
                $key,
                $matches
            ) === 1
        ) {

            array_shift($matches);

            return $matches;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
