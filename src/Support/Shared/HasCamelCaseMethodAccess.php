<?php

namespace Takemo101\CmsTool\Support\Shared;

use BadMethodCallException;
use Closure;

use function Symfony\Component\String\u;

trait HasCamelCaseMethodAccess
{
    /**
     * Undocumented function
     *
     * @return array<string,Closure():mixed> [methodNames => methodClosures]
     */
    protected function __methods(): array
    {
        return [
            // 'exampleMethod' => fn() => 'example',
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed[] $arguments
     * @throws BadMethodCallException
     */
    public function __call(string $name, array $arguments): mixed
    {
        // Convert the method name to camel case.
        $camel = u($name)->camel()->toString();

        if (method_exists($this, $camel)) {
            return $this->{$camel}(...$arguments);
        }

        // __methods() is a method that returns an array of methods.
        $data = $this->__methods();

        if (array_key_exists($name, $data)) {
            return $data[$name](...$arguments);
        }

        if (array_key_exists($camel, $data)) {
            return $data[$camel](...$arguments);
        }

        throw new BadMethodCallException("Method {$name} does not exist");
    }
}
