<?php

namespace CmsTool\View\Html\Filter;

class FormAppendFilters implements FormAppendFilter
{
    /**
     * @var FormAppendFilter[]
     */
    private array $filters = [];

    public function __construct(
        FormAppendFilter ...$filters,
    ) {
        $this->filters = $filters;
    }

    /**
     * Adds an attribute transformer to the collection.
     *
     * @param FormAppendFilter ...$filters The transformer to add.
     * @return self
     */
    public function addFilter(FormAppendFilter ...$filters): self
    {
        $this->filters = [
            ...$this->filters,
            ...$filters,
        ];

        return $this;
    }

    /**
     * Filter the elements to be added to the form by attributes
     *
     * @param array<string,mixed> $attributes
     * @return string|null
     */
    public function filter(array $attributes): ?string
    {
        /** @var string */
        $result = '';

        foreach ($this->filters as $filter) {
            if ($element = $filter->filter($attributes)) {
                $result .= $element;
            }
        }

        return $result ?: null;
    }
}
