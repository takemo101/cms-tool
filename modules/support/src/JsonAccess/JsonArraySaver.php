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
     * @param integer $flags JSON encode flags
     * @return void
     * @throws JsonNotAccessibleException|JsonConversionException
     */
    public function save(string $path, array $data, int $flags = 0): void;
}
