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
     * @param string[] $fragments
     * @param array<string,mixed>|Arrayable<string,mixed> $data
     * @param array<string,mixed> $shared
     */
    public function __construct(
        private readonly TemplateFinder $finder,
        private readonly TemplateRenderer $renderer,
        private readonly string $name,
        private array $fragments = [],
        private array|Arrayable $data = [],
        private array $shared = [],
    ) {
        //
    }

    /**
     * Add the fragments to be passed to the view.
     *
     * @param string ...$fragments
     * @return static
     */
    public function addFragment(string ...$fragments): static
    {
        $this->fragments = [
            ...$this->fragments,
            ...$fragments,
        ];

        return $this;
    }

    /**
     * Set the fragments to be passed to the view.
     *
     * @param string ...$fragments
     * @return static
     */
    public function setFragment(string ...$fragments): static
    {
        $this->fragments = $fragments;

        return $this;
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
     * If the view has fragments, the fragments will be rendered.
     *
     * @return string
     */
    public function render(): string
    {
        $path = $this->finder->find($this->name);

        return empty($this->fragments)
            ? $this->renderer->renderTemplate(
                $path,
                [
                    ...$this->shared,
                    ...($this->data instanceof Arrayable
                        ? $this->data->toArray()
                        : $this->data),
                ],
            )
            : $this->renderer->renderFragments(
                $path,
                $this->fragments,
                [
                    ...$this->shared,
                    ...($this->data instanceof Arrayable
                        ? $this->data->toArray()
                        : $this->data),
                ],
            );
    }
}
