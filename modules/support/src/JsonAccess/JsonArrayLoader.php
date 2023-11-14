<?php

namespace CmsTool\Support\JsonAccess;

use CmsTool\Support\JsonAccess\Exception\JsonNotAccessibleException;
use CmsTool\Support\JsonAccess\Exception\JsonConversionException;

interface JsonArrayLoader
{
    /**
     * Load the JSON and return the data in array format
     *
     * @param string $path
     * @return array<string,mixed>
     * @throws JsonNotAccessibleException|JsonConversionException
     */
    public function load(string $path): array;

    /**
     * Check if the JSON exists
     *
     * @param string $path
     * @return boolean
     */
    public function exists(string $path): bool;
}
