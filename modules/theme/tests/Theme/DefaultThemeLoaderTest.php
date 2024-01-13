<?php

use CmsTool\Theme\Contract\ActiveThemeIdMatcher;
use CmsTool\Theme\DefaultThemeLoader;
use CmsTool\Theme\Exception\ThemeLoadException;
use CmsTool\Theme\Theme;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Filesystem\PathHelper;
use Mockery as m;

beforeEach(function () {
    $this->matcher = m::mock(ActiveThemeIdMatcher::class);
    $this->filesystem = m::mock(LocalFilesystem::class);

    $this->loader = new DefaultThemeLoader($this->matcher, $this->filesystem, new PathHelper());
});

describe(
    'DefaultThemeLoader',
    function () {

        it('loads a theme successfully', function () {
            $path = '/path/to/theme/theme.json';
            $content = '{"uid": "uid", "name": "My Theme", "version": "1.0", "content": "content", "author": {"name": "name"}}';

            $this->filesystem->shouldReceive('read')
                ->with($path)
                ->andReturn($content);

            $this->matcher->shouldReceive('isMatch')
                ->andReturnTrue();

            $theme = $this->loader->load($path);

            expect($theme)->toBeInstanceOf(Theme::class);
        });

        it('throws an exception when content is not found', function () {
            $path = '/path/to/theme.json';

            $this->filesystem->shouldReceive('read')
                ->with($path)
                ->andReturnFalse();

            expect(fn () => $this->loader->load($path))->toThrow(ThemeLoadException::class);
        });

        it('throws an exception when content is invalid', function () {
            $path = '/path/to/theme.json';
            $content = '{"name": "My Theme", "version": "1.0",}';

            $this->filesystem->shouldReceive('read')
                ->with($path)
                ->andReturn($content);

            $this->matcher->shouldReceive('isMatch')
                ->andReturnFalse();

            expect(fn () => $this->loader->load($path))->toThrow(ThemeLoadException::class);
        });
    }
)->group('DefaultThemeLoader', 'theme');
