<?php

namespace CmsTool\Support\Validation;

use CmsTool\Support\Validation\Mapper\DefaultCaseMapper;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionNamedType;
use Takemo101\Chubby\Contract\Arrayable;

/**
 * @implements Arrayable<string,mixed>
 */
abstract class FormRequestObject implements Arrayable
{
    /**
     * Properties that exclude array
     *
     * @var string[]
     */
    public const ExcludeArrayProperties = [];

    /**
     * constructor
     *
     * @param array<string,mixed> $properties
     */
    final public function __construct(
        array $properties = [],
    ) {
        $this->populate($properties);
    }

    /**
     * Populate properties
     *
     * @param array<string,mixed> $properties
     * @return void
     */
    public function populate(array $properties): void
    {
        $reflectionClass = new ReflectionClass($this);

        $reflectionProperties = $reflectionClass->getProperties();

        $attribute = $reflectionClass->getAttributes(
            PropertyNameMapper::class,
            ReflectionAttribute::IS_INSTANCEOF,
        )[0] ?? null;

        $mapper = $attribute
            ? $attribute->newInstance()
            : new DefaultCaseMapper();

        // Convert the array key so that it can be used as a variable name.
        // Basically convert to a snake case.
        $properties = array_map(
            function ($key, $value) use ($mapper) {
                $key = trim($key);
                $key = str_replace(['-', ' '], '_', $key);
                $key = preg_replace('/[^a-zA-Z0-9_\x80-\xff]/', '', $key);

                $key = $mapper->fromKey($key);

                return [$key => $value];
            },
            array_keys($properties),
            $properties
        );

        $properties = array_merge(...$properties);

        $propertyKeys = array_keys($properties);

        foreach ($reflectionProperties as $reflectionProperty) {
            $name = $reflectionProperty->getName();
            $type = $reflectionProperty->getType();

            if (in_array($name, $propertyKeys)) {
                $value = $properties[$name];

                if (
                    is_array($value)
                    && $type
                    && ($type instanceof ReflectionNamedType)
                    && !$type->isBuiltin()
                    && is_subclass_of(
                        $typeName = $type->getName(),
                        self::class,
                    )
                ) {
                    /** @var class-string<self> */
                    $class = $typeName;

                    $value = new $class($value);
                }

                $this->{$name} = $value;
            }
        }
    }

    /**
     * Convert the object to its array representation.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $properties = get_object_vars($this);

        $attribute = (new ReflectionClass($this))->getAttributes(PropertyNameMapper::class)[0] ?? null;

        $mapper = $attribute
            ? $attribute->newInstance()
            : new DefaultCaseMapper();

        /** @var array<string,mixed> */
        $result = [];

        foreach ($properties as $key => $value) {
            if (in_array($key, static::ExcludeArrayProperties)) {
                continue;
            }

            if ($value instanceof Arrayable) {
                $value = $value->toArray();
            } elseif (is_array($value)) {
                $value = array_map(
                    fn ($item) => $item instanceof Arrayable
                        ? $item->toArray()
                        : $item,
                    $value,
                );
            }

            $key = $mapper->toKey($key);

            $result[$key] = $value;
        }

        return $result;
    }
}
