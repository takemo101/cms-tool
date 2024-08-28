<?php

namespace CmsTool\Support\Validation;

interface RequestInputs
{
    /**
     * Get input
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function input(string $key, mixed $default = null): mixed;

    /**
     * Get all inputs
     *
     * @return array<string,mixed>
     */
    public function inputs(): array;
}
