<?php

namespace CmsTool\Theme\Contract;

/**
 * @template T of object
 */
interface ThemePresetFinder
{
    /**
     * Find preset object or class name.
     *
     * @param string $name
     * @return T|class-string<T>|false
     */
    public function find(string $name): object|string|false;
}
