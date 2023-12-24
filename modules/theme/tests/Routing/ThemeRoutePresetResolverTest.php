<?php

use CmsTool\Theme\Routing\ThemeRoutePresetResolver;
use CmsTool\Theme\Routing\ThemeRoutePresets;
use CmsTool\Theme\Routing\ThemeRoute;
use CmsTool\Theme\Exception\NotFoundThemePresetException;
use DI\FactoryInterface;
use Mockery as m;

beforeEach(function () {
    $this->factory = m::mock(FactoryInterface::class);
    $this->presets = m::mock(ThemeRoutePresets::class);

    $this->resolver = new ThemeRoutePresetResolver($this->factory, $this->presets);
});

describe(
    'ThemeRoutePresetResolver',
    function () {

        it('should resolve theme route preset', function () {
            $name = 'preset_name';
            $class = 'ThemeRouteClass';
            $route = m::mock(ThemeRoute::class);

            $this->presets->shouldReceive('find')
                ->with($name)
                ->andReturn($class);

            $this->factory->shouldReceive('make')
                ->with($class)
                ->andReturn($route);

            $actual = $this->resolver->resolve($name);

            expect($actual)->toBe($route);
        });

        it('should return null when theme route preset is not found', function () {
            $name = 'non_existing_preset';

            $this->presets->shouldReceive('find')
                ->with($name)
                ->andReturn(false);

            $actual = $this->resolver->resolve($name);

            expect($actual)->toBeNull();
        });
    }
)->group('ThemeRoutePresetResolver', 'routing');
