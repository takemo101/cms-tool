<?php

namespace CmsTool\View\Accessor;

use RuntimeException;
use Closure;

/**
 * Register the accessor to the key and get a value from the accessor
 */
class DataAccessors
{
    /**
     * Value access processing
     *
     * @var array<string,DataAccessor>
     */
    private array $accessors = [];

    /**
     * Cash of values acquired from the accessor
     *
     * @var array<string,mixed>
     */
    private array $cache = [];

    /**
     * constructor
     *
     * @param DataAccessInvoker $invoker
     * @param array<string,Closure|string> $callables
     */
    public function __construct(
        private readonly DataAccessInvoker $invoker,
    ) {
        //
    }

    /**
     * Get the value from the accessor
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     * @throws RuntimeException
     */
    public function get(string $key, mixed $default = null): mixed
    {
        // If the cache has a value, return it
        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        }

        foreach ($this->accessors as $accessor) {
            $arguments = $accessor->extractArguments($key);
            if ($arguments !== false) {

                $value = $this->invoker->invoke(
                    $accessor->getAccessor(),
                    $accessor->getParameters(),
                    $arguments,
                );

                $this->cache[$key] = $value;

                return $value;
            }
        }

        return $default;
    }

    /**
     * Is there a value for the key?
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        if (array_key_exists($key, $this->cache)) {
            return true;
        }

        foreach ($this->accessors as $accessor) {
            $arguments = $accessor->extractArguments($key);

            if ($arguments !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add access processing to keys
     *
     * @param string $key
     * @param Closure|class-string<object&callable> $callable
     * @param array<string,mixed> $parameters
     * @return self
     */
    public function add(
        string $key,
        Closure|string $accessor,
        array $parameters = [],
    ): self {
        $this->accessors[$key] = new DataAccessor(
            new DataAccessKey($key),
            $accessor,
            $parameters,
        );

        return $this;
    }

    /**
     * Clear the cache
     *
     * @return self
     */
    public function clearCache(): void
    {
        $this->cache = [];
    }
}
