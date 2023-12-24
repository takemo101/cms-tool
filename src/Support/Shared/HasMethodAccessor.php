<?php

namespace Takemo101\CmsTool\Support\Shared;

use RuntimeException;

trait HasMethodAccessor
{
    /**
     * @var string[]
     */
    public const IgnoreAccessMethods = [];

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (
            method_exists($this, $name)
            && !in_array($name, [
                ...self::IgnoreAccessMethods,
                '__construct',
                '__get',
            ])
        ) {
            return call_user_func([$this, $name]);
        }

        throw new RuntimeException(sprintf('property %s not found', $name));
    }
}
