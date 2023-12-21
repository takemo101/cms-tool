<?php

namespace CmsTool\Theme\Routing;

use DI\FactoryInterface;

class ThemeRoutePresetResolver
{
    /**
     * constructor
     *
     * @param FactoryInterface $factory
     */
    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly ThemeRoutePresets $presets,
    ) {
        //
    }

    /**
     * Resolve theme route preset
     *
     * @param string $name
     * @return ThemeRoute
     * @throws NotFoundThemePresetException
     */
    public function resolve(string $name): ThemeRoute
    {
        $class = $this->presets->find($name);

        if (!is_string($class)) {
            return $class;
        }

        /** @var ThemeRoute */
        $route = $this->factory->make($class);

        return $route;
    }
}
