<?php

namespace CmsTool\Support\Validation;

interface PropertyNameMapper
{
    /**
     * Convert property name
     *
     * @param string $name
     * @return string
     */
    public function fromKey(string $name): string;

    /**
     * Convert property name
     *
     * @param string $name
     * @return string
     */
    public function toKey(string $name): string;
}
