<?php

namespace CmsTool\Theme\Routing;

use CmsTool\Theme\ThemePresetResolver;
use DI\FactoryInterface;

/**
 * @extends ThemePresetResolver<ThemeRoute>
 */
class ThemeRoutePresetResolver extends ThemePresetResolver
{
    /**
     * constructor
     *
     * @param FactoryInterface $factory
     * @param ThemeRoutePresets $presets
     */
    public function __construct(
        FactoryInterface $factory,
        ThemeRoutePresets $presets,
    ) {
        parent::__construct($factory, $presets);
    }
}
