<?php

namespace CmsTool\Support\JsonAccess;

use CmsTool\Support\JsonAccess\Exception\JsonNotAccessibleException;
use CmsTool\Support\JsonAccess\Exception\JsonConversionException;
use Takemo101\Chubby\Contract\Arrayable;
use Illuminate\Support\Arr;

/**
 * @implements Arrayable<string,mixed>
 */
class JsonAccessObject implements Arrayable
{
    /**
     * constructor
     *
     * @param JsonArraySaver $saver
     * @param string $path
     * @param array<string,mixed> $data
     */
    public function __construct(
        private readonly JsonArraySaver $saver,
        private string $path,
        private array $data = [],
    ) {
        //
    }

    /**
     * Get all data
     *
     * @return array<string,mixed>
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Get the data of the specified key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * Set the data on the specified key
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        Arr::set($this->data, $key, $value);
    }

    /**
     * Delete the data of the specified key
     *
     * @param string $key
     * @return void
     */
    public function delete(string $key): void
    {
        Arr::forget($this->data, $key);
    }

    /**
     * Whether the specified key exists
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        return Arr::has($this->data, $key);
    }

    /**
     * Save the current data in JSON format
     *
     * @return void
     * @throws JsonNotAccessibleException|JsonConversionException
     */
    public function save(): void
    {
        $this->saver->save(
            $this->path,
            $this->data,
        );
    }

    /**
     * Convert the object to its array representation.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
