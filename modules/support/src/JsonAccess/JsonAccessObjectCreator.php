<?php

namespace CmsTool\Support\JsonAccess;

use CmsTool\Support\JsonAccess\Exception\JsonNotAccessibleException;
use CmsTool\Support\JsonAccess\Exception\JsonConversionException;

class JsonAccessObjectCreator
{
    /**
     * @var array<string,JsonAccessObject>
     */
    private array $objects = [];

    /**
     * constructor
     *
     * @param JsonArrayAccessor $accessor
     */
    public function __construct(
        private readonly JsonArrayAccessor $accessor,
    ) {
        //
    }

    /**
     * Create a new json access object
     *
     * @param string $path
     * @param array<string,mixed> $default
     * @return JsonAccessObject
     * @throws JsonNotAccessibleException|JsonConversionException
     */
    public function create(
        string $path,
        array $default = [],
    ): JsonAccessObject {

        if (isset($this->objects[$path])) {
            return $this->objects[$path];
        }

        $object = JsonAccessObject::fromArray(
            saver: $this->accessor,
            path: $path,
            data: $this->accessor->exists($path)
                ? $this->accessor->load($path)
                : $default,
        );

        $this->objects[$path] = $object;

        return $object;
    }
}
