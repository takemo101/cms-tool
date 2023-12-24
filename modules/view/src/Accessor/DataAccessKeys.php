<?php

namespace CmsTool\View\Accessor;

/**
 * Key for accessing data
 */
class DataAccessKeys
{
    /** @var DataAccessKey[] */
    public readonly array $keys;

    /**
     * constructor
     *
     * @param DataAccessKey ...$keys
     */
    public function __construct(
        DataAccessKey ...$keys
    ) {
        $this->keys = $keys;
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
        foreach ($this->keys as $dataAccessKey) {
            $arguments = $dataAccessKey->extractArguments($key);

            if ($arguments !== false) {
                return $arguments;
            }
        }

        return false;
    }

    /**
     * Create an instance from a string
     *
     * @param string ...$keys
     * @return self
     */
    public static function fromStrings(string ...$keys): self
    {
        $keys = array_unique($keys);

        $instances = array_map(
            fn (string $key) => new DataAccessKey($key),
            $keys
        );

        return new self(...$instances);
    }
}
