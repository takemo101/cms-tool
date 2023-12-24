<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Contract\ThemePresetFinder;
use DI\FactoryInterface;

/**
 * @template T of object
 */
abstract class ThemePresetResolver
{
    /**
     * constructor
     *
     * @param FactoryInterface $factory
     * @param ThemePresetFinder<T> $finder
     */
    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly ThemePresetFinder $finder,
    ) {
        //
    }

    /**
     * Resolve theme route preset
     *
     * @param string $name
     * @return T|null
     */
    public function resolve(string $name): ?object
    {
        $class = $this->finder->find($name);

        if ($class === false) {
            return null;
        }

        if (!is_string($class)) {
            return $class;
        }

        /** @var T */
        $preset = $this->factory->make($class);

        return $preset;
    }
}
