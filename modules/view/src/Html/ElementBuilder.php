<?php

namespace CmsTool\View\Html;

final class ElementBuilder
{
    /**
     * constructor
     *
     * @param AttributeBuilder $builder
     */
    public function __construct(
        private AttributeBuilder $builder = new AttributeBuilder(),
    ) {
        //
    }

    /**
     * Build element
     *
     * @param string $name
     * @param array<string,mixed> $attributes
     * @param string|null $content If Content is null, return the self -termination tag
     * @return string
     */
    public function build(string $name, array $attributes = [], ?string $content = null): string
    {
        if (is_null($content)) {
            return $this->buildOpen($name, $attributes, true);
        }

        $element = $this->buildOpen($name, $attributes);
        $element .= $content;
        $element .= $this->buildClose($name);

        return $element;
    }

    /**
     * Build element open
     *
     * @param string $name
     * @param array<string,mixed> $attributes
     * @param bool $termination If true, return the self -termination tag
     * @return string
     */
    public function buildOpen(string $name, array $attributes = [], bool $termination = false): string
    {
        $prefix = empty($attributes)
            ? "<{$name}"
            : "<{$name} {$this->builder->build($attributes)}";

        return $prefix . ($termination ? ' />' : '>');
    }

    /**
     * Build element close
     *
     * @param string $name
     * @return string
     */
    public function buildClose(string $name): string
    {
        return "</{$name}>";
    }
}
