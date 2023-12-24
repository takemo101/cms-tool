<?php

namespace CmsTool\View\Accessor;

use Closure;

/**
 * Key for accessing data
 */
class DataAccessor
{
    /**
     * constructor
     *
     * @param DataAccessKeys $keys
     * @param Closure|class-string<object&callable> $accessor
     * @param array<string,mixed> $parameters
     */
    public function __construct(
        private readonly DataAccessKeys $keys,
        private readonly Closure|string $accessor,
        private readonly array $parameters = [],
    ) {
        //
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
        return $this->keys->extractArguments($key);
    }

    /**
     * Get the accessor
     *
     * @return Closure|class-string<object&callable>
     */
    public function getAccessor(): Closure|string
    {
        return $this->accessor;
    }

    /**
     * Get accessor parameters
     *
     * @return array<string,mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
