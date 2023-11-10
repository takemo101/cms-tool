<?php

namespace CmsTool\View;

use CmsTool\View\Contract\TemplateFinder;
use CmsTool\View\Contract\TemplateRenderer;
use Takemo101\Chubby\Contract\Arrayable;
use Takemo101\Chubby\Contract\Renderable;

class View implements Renderable
{
    /**
     * constructor
     *
     * @param TemplateFinder $finder
     * @param TemplateRenderer $renderer
     * @param string $name
     * @param array<string,mixed>|Arrayable<string,mixed> $data
     * @param array<string,mixed> $shared
     */
    final public function __construct(
        private readonly TemplateFinder $finder,
        private readonly TemplateRenderer $renderer,
        private readonly string $name,
        private array|Arrayable $data = [],
        private array $shared = [],
    ) {
        //
    }

    /**
     * Set the data to be passed to the view.
     *
     * @param array<string,mixed>|Arrayable<string,mixed> $data
     * @return static
     */
    public function setData(array|Arrayable $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the string contents of the view.
     *
     * @return string
     */
    public function render(): string
    {
        return $this->renderer->renderTemplate(
            $this->finder->find($this->name),
            [
                ...$this->shared,
                ...($this->data instanceof Arrayable
                    ? $this->data->toArray()
                    : $this->data),
            ],
        );
    }
}
