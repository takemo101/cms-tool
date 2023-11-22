<?php

namespace CmsTool\Support\JsonAccess;

use CmsTool\Support\JsonAccess\Exception\JsonConversionException;
use CmsTool\Support\JsonAccess\Exception\JsonNotAccessibleException;
use CmsTool\Support\JsonAccess\Exception\NotFoundJsonException;
use CmsTool\Support\JsonAccess\Exception\JsonAccessException;
use Takemo101\Chubby\Filesystem\LocalFilesystem;

class DefaultJsonAccessor implements JsonArrayAccessor
{
    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     */
    public function __construct(
        private readonly LocalFilesystem $filesystem,
    ) {
        //
    }

    /**
     * Load the JSON and return the data in array format
     *
     * @param string $path
     * @return array<string,mixed>
     * @throws JsonAccessException
     */
    public function load(string $path): array
    {
        if (
            !$this->filesystem->exists($path) ||
            !$this->filesystem->isFile($path)
        ) {
            throw new NotFoundJsonException($path);
        }

        if (
            !$this->filesystem->isReadable($path)
        ) {
            throw JsonNotAccessibleException::notReadableError($path);
        }

        $json = $this->filesystem->read($path);

        if (is_null($json)) {
            throw JsonNotAccessibleException::notReadableError($path);
        }

        /** @var array<string,mixed>|boolean|null */
        $data = json_decode($json, true);

        if (
            !is_array($data)
            || json_last_error() !== JSON_ERROR_NONE
        ) {
            throw JsonConversionException::decodeError($path);
        }

        return $data;
    }

    /**
     * Check if the JSON exists
     *
     * @param string $path
     * @return boolean
     */
    public function exists(string $path): bool
    {
        return $this->filesystem->exists($path);
    }

    /**
     * Save the data in array format to the JSON
     *
     * @param string $path
     * @param array<string,mixed> $data
     * @return void
     * @throws JsonAccessException
     */
    public function save(string $path, array $data): void
    {
        try {
            $json = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        } catch (\JsonException $e) {
            throw JsonConversionException::encodeError($path, $e);
        }

        if (!$json) {
            throw JsonConversionException::encodeError($path);
        }

        if (!$this->filesystem->write($path, $json)) {
            throw JsonNotAccessibleException::notWritableError($path);
        }
    }
}
