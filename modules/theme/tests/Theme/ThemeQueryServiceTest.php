<?php

use CmsTool\Theme\ThemeQueryService;
use CmsTool\Theme\Contract\ThemeFinder;
use CmsTool\Theme\Contract\ThemeLoader;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\Theme;
use CmsTool\Theme\Exception\NotFoundThemeException;
use Mockery as m;

beforeEach(function () {
    $this->finder = m::mock(ThemeFinder::class);
    $this->loader = m::mock(ThemeLoader::class);

    $this->service = new ThemeQueryService($this->finder, $this->loader);
});

describe(
    'ThemeQueryService',
    function () {

        it('returns the theme when it exists', function () {
            $id = m::mock(ThemeId::class);
            $path = '/path/to/theme/theme.json';
            $theme = m::mock(Theme::class);

            $this->finder->shouldReceive('find')
                ->with($id)
                ->andReturn($path);

            $this->loader->shouldReceive('load')
                ->with($path)
                ->andReturn($theme);

            $result = $this->service->getOne($id);

            expect($result)->toBe($theme);
        });

        it('throws an exception when the theme does not exist', function () {
            $id = m::mock(ThemeId::class);

            $this->finder->shouldReceive('find')
                ->with($id)
                ->andThrow(NotFoundThemeException::class);

            expect(fn () => $this->service->getOne($id))->toThrow(NotFoundThemeException::class);
        });

        it('returns an array of themes', function () {
            $paths = ['/path/to/theme1/theme.json', '/path/to/theme2/theme.json'];
            $themes = [m::mock(Theme::class), m::mock(Theme::class)];

            $this->finder->shouldReceive('findAll')
                ->andReturn($paths);

            $this->loader->shouldReceive('load')
                ->with('/path/to/theme1/theme.json')
                ->andReturn($themes[0]);

            $this->loader->shouldReceive('load')
                ->with('/path/to/theme2/theme.json')
                ->andReturn($themes[1]);

            $result = $this->service->getAll();

            expect($result)->toBe($themes);
        });
    }
)->group('ThemeQueryService', 'theme');
