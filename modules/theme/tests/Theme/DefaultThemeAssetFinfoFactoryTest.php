<?php

use CmsTool\Theme\DefaultThemeAssetFinfoFactory;
use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemePathHelper;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Mockery as m;

beforeEach(function () {
    $this->filesystem = m::mock(LocalFilesystem::class);
    $this->helper = m::mock(ThemePathHelper::class);
    $this->factory = new DefaultThemeAssetFinfoFactory($this->filesystem, $this->helper);
});

describe(
    'DefaultThemeAssetFinfoFactory',
    function () {

        it('returns null if asset does not exist', function () {
            $theme = m::mock(Theme::class);
            $paths = ['css', 'main.css'];

            $this->helper->shouldReceive('getAssetPath')
                ->with($theme, ...$paths)
                ->andReturn('/path/to/theme/css/main.css');

            $this->filesystem->shouldReceive('exists')
                ->with('/path/to/theme/css/main.css')
                ->andReturnFalse();

            $result = $this->factory->create($theme, ...$paths);

            expect($result)->toBeNull();
        });

        it('returns null if asset is not a file', function () {
            $theme = m::mock(Theme::class);
            $paths = ['css', 'main.css'];

            $this->helper->shouldReceive('getAssetPath')
                ->with($theme, ...$paths)
                ->andReturn('/path/to/theme/css/main.css');

            $this->filesystem->shouldReceive('exists')
                ->with('/path/to/theme/css/main.css')
                ->andReturnTrue();

            $this->filesystem->shouldReceive('isFile')
                ->with('/path/to/theme/css/main.css')
                ->andReturnFalse();

            $result = $this->factory->create($theme, ...$paths);

            expect($result)->toBeNull();
        });

        it('returns SplFileInfo object if asset exists and is a file', function () {
            $theme = m::mock(Theme::class);
            $paths = ['css', 'main.css'];

            $this->helper->shouldReceive('getAssetPath')
                ->with($theme, ...$paths)
                ->andReturn('/path/to/theme/css/main.css');

            $this->filesystem->shouldReceive('exists')
                ->with('/path/to/theme/css/main.css')
                ->andReturnTrue();

            $this->filesystem->shouldReceive('isFile')
                ->with('/path/to/theme/css/main.css')
                ->andReturnTrue();

            $result = $this->factory->create($theme, ...$paths);

            expect($result)->toBeInstanceOf(SplFileInfo::class);
            expect($result->getPathname())->toBe('/path/to/theme/css/main.css');
        });
    }
)->group('DefaultThemeAssetFinfoFactory', 'theme');
