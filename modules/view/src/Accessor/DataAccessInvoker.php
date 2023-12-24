<?php

namespace CmsTool\View\Accessor;

use RuntimeException;
use Closure;

/**
 * Execute the value acquisition process from the accessor
 */
interface DataAccessInvoker
{
    /**
     * Execute the value acquisition process from the accessor
     * Return the target value
     *
     * @param Closure|class-string<object&callable> $accessor
     * @param array<string,mixed> $parameters
     * @param string[] $arguments
     * @return mixed
     * @throws RuntimeException
     */
    public function invoke(
        Closure|string $callable,
        array $parameters = [],
        array $arguments = [],
    ): mixed;
}
