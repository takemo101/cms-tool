<?php

namespace Takemo101\CmsTool\Support\Shared;

use BadMethodCallException;

use function Symfony\Component\String\u;

trait HasCamelCasePropertyAccess
{
    /**
     * Get the value corresponding to the property name.
     *
     * @return array<string,mixed> [propertyNames => propertyValues]
     */
    protected function __properties(): array
    {
        return [
            // 'exampleProperty' => $this->example,
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @param string $name
     * @return boolean
     */
    public function __isset(string $name): bool
    {
        // Convert the property name to camel case.
        $camel = u($name)->camel()->toString();

        if (property_exists($this, $camel)) {
            return true;
        }

        // __properties() is a method that returns an array of properties.
        $data = $this->__properties();

        if (
            array_key_exists($name, $data) ||
            array_key_exists($camel, $data)
        ) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @throws BadMethodCallException
     */
    public function __get(string $name): mixed
    {
        // Convert the property name to camel case.
        $camel = u($name)->camel()->toString();

        if (property_exists($this, $camel)) {
            return $this->{$camel};
        }

        // __properties() is a method that returns an array of properties.
        $data = $this->__properties();

        if (array_key_exists($name, $data)) {
            return $data[$name];
        }

        if (array_key_exists($camel, $data)) {
            return $data[$camel];
        }

        throw new BadMethodCallException("Property {$name} does not exist");
    }
}
