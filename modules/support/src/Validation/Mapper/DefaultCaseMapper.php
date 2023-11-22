<?php

namespace CmsTool\Support\Validation\Mapper;

use CmsTool\Support\Validation\PropertyNameMapper;

class DefaultCaseMapper implements PropertyNameMapper
{
    /**
     * Convert property name
     *
     * @param string $name
     * @return string
     */
    public function fromKey(string $name): string
    {
        return $name;
    }

    /**
     * Convert property name
     *
     * @param string $name
     * @return string
     */
    public function toKey(string $name): string
    {
        return $name;
    }
}
