<?php

use CmsTool\Theme\ActiveTheme;
use CmsTool\Theme\ActiveThemeFactory;
use CmsTool\Theme\ActiveThemeId;
use CmsTool\Theme\Theme;
use CmsTool\Theme\Contract\ThemeFinder;
use CmsTool\Theme\Contract\ThemeLoader;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemeSetting;
use Mockery as m;

beforeEach(function () {
    $this->loader = m::mock(ThemeLoader::class);
    $this->finder = m::mock(ThemeFinder::class);
    $this->id = m::mock(ActiveThemeId::class);

    $this->factory = new ActiveThemeFactory($this->loader, $this->finder, $this->id);
});

describe(
    'ActiveThemeFactory',
    function () {

        it('creates the active theme successfully', function () {
            $path = '/path/to/theme/theme.json';
            $theme = new Theme(
                id: new ThemeId('id'),
                directory: 'directory',
                active: false,
                setting: ThemeSetting::fromArray([
                    'uid' => 'uid',
                    'name' => 'name',
                    'version' => 'version',
                    'content' => 'content',
                    'author' => [
                        'name' => 'name',
                    ],
                ]),
            );

            $this->id->shouldReceive('value')
                ->andReturn('theme');

            $this->finder->shouldReceive('find')
                ->andReturn($path);

            $this->loader->shouldReceive('load')
                ->with($path)
                ->andReturn($theme);

            $result = $this->factory->create();

            expect($result)->toBeInstanceOf(ActiveTheme::class);
            expect($result->isActive())->toBeTrue();
        });
    }
)->group('ActiveThemeFactory', 'theme');
