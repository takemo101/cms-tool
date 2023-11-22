<?php

namespace CmsTool\View\Html\Filter;

class AppendMethodOverrideInputFilter implements FormAppendFilter
{
    /** @var string[] */
    public const SpoofedMethods = [
        'PUT',
        'PATCH',
        'DELETE',
    ];
    /**
     * Filter the elements to be added to the form by attributes
     *
     * @param array<string,mixed> $attributes
     * @return string|null
     */
    public function filter(array $attributes): ?string
    {
        /** @var string */
        $method = $attributes['method'] ?? 'GET';

        if (!in_array($method, self::SpoofedMethods)) {
            return null;
        }

        return sprintf(
            '<input type="hidden" name="_METHOD" value="%s" />',
            $method,
        );
    }
}
