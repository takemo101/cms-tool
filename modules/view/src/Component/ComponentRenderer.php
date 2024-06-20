<?php

namespace CmsTool\View\Component;

use RuntimeException;
use Closure;
use CmsTool\View\Contract\Htmlable;
use CmsTool\View\HtmlObject;
use DI\FactoryInterface;
use Stringable;
use Takemo101\Chubby\Contract\Renderable;

/**
 * Render the component
 */
class ComponentRenderer
{
    /**
     * constructor
     *
     * @param FactoryInterface $factory
     */
    public function __construct(
        private FactoryInterface $factory,
    ) {
        //
    }

    /**
     * Render the component callable
     *
     * @param Closure|class-string<object&callable> $callable
     * @param mixed[] $arguments
     * @return Htmlable
     * @throws RuntimeException
     */
    public function render(
        Closure|string $callable,
        array $arguments = [],
    ): mixed {
        if ($callable instanceof Closure) {
            $result = call_user_func_array($callable, $arguments);
        } else {

            if (!class_exists($callable)) {
                throw new RuntimeException("The component class does not exist: {$callable}");
            }

            /** @var object&callable  */
            $component = $this->factory->make($callable);

            if (!is_callable($component)) {
                throw new RuntimeException("The component class is not callable: {$callable}");
            }

            $result = call_user_func_array($component, $arguments);
        }

        return match (true) {
            $result instanceof Htmlable => $result,
            $result instanceof Renderable => new HtmlObject($result->render()),
            $result instanceof Stringable => new HtmlObject($result->__toString()),
            is_string($result) => new HtmlObject($result),
            default => $result,
        };
    }
}
