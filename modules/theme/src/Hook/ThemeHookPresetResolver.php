<?php

namespace CmsTool\Theme\Hook;

use CmsTool\Theme\ThemePresetResolver;
use DI\FactoryInterface;

/**
 * @extends ThemePresetResolver<ThemeHook>
 */
class ThemeHookPresetResolver extends ThemePresetResolver
{
    /**
     * constructor
     *
     * @param FactoryInterface $factory
     * @param ThemeHookPresets $presets
     */
    public function __construct(
        FactoryInterface $factory,
        ThemeHookPresets $presets,
    ) {
        parent::__construct($factory, $presets);
    }
}
