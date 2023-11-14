<?php

namespace CmsTool\Support\JsonAccess;

use CmsTool\Support\JsonAccess\Exception\JsonNotAccessibleException;
use CmsTool\Support\JsonAccess\Exception\JsonConversionException;

interface JsonArraySaver
{
    /**
     * Save the data in array format to the JSON
     *
     * @param string $path
     * @param array<string,mixed> $data
     * @return void
     * @throws JsonNotAccessibleException|JsonConversionException
     */
    public function save(string $path, array $data): void;
}
