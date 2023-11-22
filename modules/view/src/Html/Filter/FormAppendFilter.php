<?php

namespace CmsTool\View\Html\Filter;

interface FormAppendFilter
{
    /**
     * Filter the elements to be added to the form by attributes
     *
     * @param array<string,mixed> $attributes
     * @return string|null
     */
    public function filter(array $attributes): ?string;
}
