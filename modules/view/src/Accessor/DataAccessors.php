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
     * @var DataAccessor[]
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
     * Get the value from the accessor
     *
     * @param string $key
     * @param mixed[] $arguments
     * @return mixed
     * @throws RuntimeException
     */
    public function call(string $key, array $arguments = []): mixed
    {
        $cacheKey = $this->buildCallCacheKey($key, $arguments);

        // If the cache has a value, return it
        if (array_key_exists($cacheKey, $this->cache)) {
            return $this->cache[$cacheKey];
        }

        foreach ($this->accessors as $accessor) {
            $extractArguments = $accessor->extractArguments($key);
            if ($extractArguments !== false) {

                $joinedArguments = [
                    ...$extractArguments,
                    ...$arguments,
                ];

                $value = $this->invoker->invoke(
                    $accessor->getAccessor(),
                    $accessor->getParameters(),
                    $joinedArguments,
                );

                $this->cache[$cacheKey] = $value;

                return $value;
            }
        }

        throw new RuntimeException("The key '{$key}' is not registered.");
    }

    /**
     * Create a cache key for the ``call`` method
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     * @throws RuntimeException
     */
    private function buildCallCacheKey(string $key, array $arguments): string
    {
        $query = http_build_query($arguments);

        return $key . (
            $query
            ? '?' . $query
            : ''
        );
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
     * @param string|string[] $key
     * @param Closure|class-string<object&callable> $callable
     * @param array<string,mixed> $parameters
     * @return self
     */
    public function add(
        string|array $key,
        Closure|string $accessor,
        array $parameters = [],
    ): self {

        $keys = is_array($key) ? $key : [$key];

        $this->accessors[] = new DataAccessor(
            DataAccessKeys::fromStrings(...$keys),
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
