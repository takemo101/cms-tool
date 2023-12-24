<?php

use CmsTool\Theme\Routing\ThemeRoutePresets;
use CmsTool\Theme\Routing\ThemeRoute;
use CmsTool\Theme\Exception\NotFoundThemePresetException;
use Mockery as m;

beforeEach(function () {
    $this->preset1 = m::mock(ThemeRoute::class);
    $this->preset2 = m::mock(ThemeRoute::class);

    $this->presets = [
        'preset1' => $this->preset1,
        'preset2' => $this->preset2,
    ];

    $this->themeRoutePresets = new ThemeRoutePresets($this->presets);
});

describe(
    'ThemeRoutePresets',
    function () {

        it('adds a route preset', function () {
            $name = 'preset3';
            $preset3 = m::mock(ThemeRoute::class);

            $this->themeRoutePresets->add($name, $preset3);

            expect($this->themeRoutePresets->find($name))->toBe($preset3);
        });

        it('throws an exception when adding a route preset with non-existing class', function () {
            $name = 'preset4';
            $class = 'NonExistingClass';

            expect(fn () => $this->themeRoutePresets->add($name, $class))->toThrow(RuntimeException::class);
        });

        it('throws an exception when adding a route preset with class that is not a subclass of ThemeRoute', function () {
            $name = 'preset5';
            $class = 'stdClass';

            expect(fn () => $this->themeRoutePresets->add($name, $class))
                ->toThrow(RuntimeException::class);
        });

        it('finds a route preset', function () {
            $name = 'preset1';

            $result = $this->themeRoutePresets->find($name);

            expect($result)->toBe($this->preset1);
        });

        it('return false when finding a non-existing route preset', function () {
            $name = 'nonExistingPreset';

            $result = $this->themeRoutePresets->find($name);

            expect($result)->toBeFalse();
        });

        it('returns all route presets', function () {
            $result = $this->themeRoutePresets->presets();

            expect($result)->toBe($this->presets);
        });
    }
)->group('ThemeRoutePresets', 'routing');
