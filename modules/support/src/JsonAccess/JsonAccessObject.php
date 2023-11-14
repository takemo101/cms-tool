<?php

namespace CmsTool\Support\JsonAccess;

use CmsTool\Support\JsonAccess\Exception\JsonNotAccessibleException;
use CmsTool\Support\JsonAccess\Exception\JsonConversionException;
use Illuminate\Support\Collection;
use Takemo101\Chubby\Contract\Arrayable;

class JsonAccessObject implements Arrayable
{
    /**
     * constructor
     *
     * @param JsonArraySaver $saver
     * @param string $path
     * @param Collection<string,mixed> $data
     */
    public function __construct(
        private readonly JsonArraySaver $saver,
        private string $path,
        private Collection $data = new Collection(),
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
        return $this->data->all();
    }

    /**
     * Get the data of the specified key
     *
     * @param string $key
     * @param mixed $default
     * @return void
     */
    public function get(string $key, mixed $default = null)
    {
        return $this->data->get($key, $default);
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
        $this->data->put($key, $value);
    }

    /**
     * Delete the data of the specified key
     *
     * @param string $key
     * @return void
     */
    public function delete(string $key): void
    {
        $this->data->forget($key);
    }

    /**
     * Whether the specified key exists
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        return $this->data->has($key);
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
            $this->data->all(),
        );
    }

    /**
     * Convert the object to its array representation.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return $this->data->toArray();
    }

    /**
     * Create a new instance from the specified path
     *
     * @param JsonArraySaver $saver
     * @param string $path
     * @param array<string,mixed> $data
     * @return static
     */
    public static function fromArray(
        JsonArraySaver $saver,
        string $path,
        array $data = [],
    ): static {
        return new static(
            saver: $saver,
            path: $path,
            data: new Collection($data),
        );
    }
}
