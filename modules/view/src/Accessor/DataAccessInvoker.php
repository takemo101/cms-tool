<?php

namespace CmsTool\View\Accessor;

use DI\FactoryInterface;
use RuntimeException;
use Closure;

class DataAccessInvoker
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
     * {@inheritDoc}
     */
    public function invoke(
        Closure|string $accessor,
        array $parameters = [],
        array $arguments = []
    ): mixed {
        if ($accessor instanceof Closure) {
            return call_user_func_array($accessor, $arguments);
        }

        if (!class_exists($accessor)) {
            throw new RuntimeException("The accessor class does not exist: {$accessor}");
        }

        /** @var object&callable  */
        $callable = $this->factory->make($accessor, $parameters);

        if (!is_callable($callable)) {
            throw new RuntimeException("The accessor class is not callable: {$accessor}");
        }

        return call_user_func_array($callable, $arguments);
    }
}
