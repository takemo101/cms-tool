<?php

namespace CmsTool\View;

use CmsTool\View\Contract\TemplateFinder;
use CmsTool\View\Contract\TemplateRenderer;
use Takemo101\Chubby\Contract\Arrayable;

class ViewCreator
{
    /**
     * @var array<string,mixed>
     */
    private array $shared = [];

    /**
     * constructor
     *
     * @param TemplateFinder $finder
     * @param TemplateRenderer $renderer
     */
    final public function __construct(
        private TemplateFinder $finder,
        private TemplateRenderer $renderer,
    ) {
        //
    }

    /**
     * Create a new view instance from the given arguments.
     *
     * @param string $name
     * @param array<string,mixed>|Arrayable<string,mixed> $data
     * @return View
     */
    public function create(string $name, array|Arrayable $data = []): View
    {
        return new View(
            finder: $this->finder,
            renderer: $this->renderer,
            name: $name,
            data: $data,
            shared: $this->shared,
        );
    }

    /**
     * Create a new view string from the given arguments.
     *
     * @param string $template
     * @param array<string,mixed>|Arrayable<string,mixed> $data
     * @return string
     */
    public function createString(string $template, array|Arrayable $data = []): string
    {
        return $this->renderer->renderString(
            $template,
            [
                ...$this->shared,
                ...($data instanceof Arrayable
                    ? $data->toArray()
                    : $data),
            ],
        );
    }

    /**
     * Share a piece of data across all views.
     *
     * @param string $key
     * @param mixed $value
     * @return static
     */
    public function share(string $key, mixed $value): static
    {
        $this->shared[$key] = $value;

        return $this;
    }

    /**
     * Get all of the shared data for the creator.
     *
     * @return array<string,mixed>
     */
    public function getShared(): array
    {
        return $this->shared;
    }

    /**
     * Set the template finder implementation.
     *
     * @param TemplateFinder $finder
     * @return static
     */
    public function setFinder(TemplateFinder $finder): static
    {
        $this->finder = $finder;

        return $this;
    }

    /**
     * Get the template finder implementation.
     *
     * @return TemplateRenderer
     */
    public function getFinder(): TemplateFinder
    {
        return $this->finder;
    }

    /**
     * Set the template renderer implementation.
     *
     * @param TemplateRenderer $renderer
     * @return static
     */
    public function setRederer(TemplateRenderer $renderer): static
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * Get the template renderer implementation.
     *
     * @return TemplateRenderer
     */
    public function getRenderer(): TemplateRenderer
    {
        return $this->renderer;
    }
}
