<?php

namespace CmsTool\Support\Validation\Mapper;

use CmsTool\Support\Validation\PropertyNameMapper;
use function Symfony\Component\String\u;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class CamelCaseMapper implements PropertyNameMapper
{
    /**
     * Convert property name
     *
     * @param string $name
     * @return string
     */
    public function fromKey(string $name): string
    {
        return u($name)->camel();
    }

    /**
     * Convert property name
     *
     * @param string $name
     * @return string
     */
    public function toKey(string $name): string
    {
        return u($name)->snake();
    }
}
