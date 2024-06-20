<?php

namespace CmsTool\View\Component;

use Closure;
use Stringable;
use Takemo101\Chubby\Contract\Renderable;

class Components
{
    /**
     * @var array<string,Closure|class-string<object&callable>>
     */
    private array $components = [];

    /**
     * constructor
     *
     * @param ComponentRenderer $renderer
     * @param array<string,Closure|class-string<object&callable> $components
     */
    public function __construct(
        private readonly ComponentRenderer $renderer,
        array $components = [],
    ) {
        foreach ($components as $name => $callable) {
            $this->add($name, $callable);
        }
    }

    /**
     * Register the component
     *
     * @param string $name
     * @param Closure|class-string<object&callable> $callable
     * @return self
     * @throws ComponentException
     */
    public function add(
        string $name,
        Closure|string $callable,
    ): self {

        if (array_key_exists($name, $this->components)) {
            throw ComponentException::alreadyExists($name);
        }

        $this->components[$name] = $callable;

        return $this;
    }

    /**
     * Get the component callable
     *
     * @param string $name
     * @return Closure|string|false
     */
    public function get(string $name): Closure|string|false
    {
        return $this->components[$name] ?? false;
    }

    /**
     * Check if the component exists
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->components);
    }

    /**
     * Render the component
     *
     * @param string $name
     * @param mixed[] $arguments
     * @return Renderable|Stringable|string|null
     * @throws ComponentException
     */
    public function render(string $name, array $arguments = []): mixed
    {
        $callable = $this->get($name);

        if ($callable === false) {
            throw ComponentException::notFound($name);
        }

        return $this->renderer->render($callable, $arguments);
    }
}
