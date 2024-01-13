<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Contract\ThemePresetFinder;
use RuntimeException;
use stdClass;

/**
 * @template T of object
 * @implements ThemePresetFinder<T>
 */
abstract class ThemePresets implements ThemePresetFinder
{
    /**
     * @var class-string<T>
     */
    public const Type = stdClass::class;

    /**
     * @var array<string,T|class-string<T>>
     */
    private array $presets = [];

    /**
     * @param array<string,T|class-string<T>> $presets
     */
    final public function __construct(array $presets)
    {
        foreach ($presets as $name => $class) {
            $this->add($name, $class);
        }
    }

    /**
     * Add preset
     *
     * @param string $name
     * @param T|class-string<T> $class
     * @return self<T>
     * @throws RuntimeException
     */
    public function add(string $name, object|string $class): self
    {
        if (is_string($class) && class_exists($class) === false) {
            throw new RuntimeException("Class {$class} not found.");
        }

        if (!(
            is_a($class, static::Type, true) // @phpstan-ignore-line
            || is_subclass_of($class, static::Type, true)
        )) {
            $className = is_object($class) ? get_class($class) : $class;

            throw new RuntimeException("Class {$className} is not a subclass of " . static::Type);
        }

        $this->presets[$name] = $class;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function find(string $name): object|string|false
    {
        return $this->presets[$name] ?? false;
    }

    /**
     * Get all presets
     *
     * @return array<string,T|class-string<T>>
     */
    public function presets(): array
    {
        return $this->presets;
    }
}
