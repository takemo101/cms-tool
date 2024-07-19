<?php

use CmsTool\Theme\Schema\DefaultThemeCustomizationAccessor;
use CmsTool\Theme\Exception\ThemeLoadException;
use CmsTool\Theme\Exception\ThemeSaveException;
use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemePathHelper;
use Mockery as m;
use Takemo101\Chubby\Filesystem\LocalFilesystem;

describe(
    'DefaultThemeCustomizationAccessor',
    function () {
        beforeEach(function () {
            $this->filesystem = m::mock(LocalFilesystem::class);
            $this->helper = m::mock(ThemePathHelper::class);
            $this->accessor = new DefaultThemeCustomizationAccessor($this->filesystem, $this->helper);
        });

        it('should save the customization data to the filesystem', function () {
            $theme = m::mock(Theme::class);
            $data = ['color' => 'blue'];
            $json = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);

            $this->helper->shouldReceive('getCustomizationDataPath')
                ->once()
                ->with($theme)
                ->andReturn('/path/to/customization.json');

            $this->filesystem->shouldReceive('write')
                ->once()
                ->with('/path/to/customization.json', $json)
                ->andReturn(true);

            $theme->shouldReceive('refineCustomizationWithDefaults')
                ->once()
                ->with($data)
                ->andReturn($data);

            $this->accessor->save($theme, $data);
        });

        it('should throw an exception if encoding the data fails', function () {
            $theme = m::mock(Theme::class);
            $data = ['color' => "\xB1\x31"];

            $this->helper->shouldReceive('getCustomizationDataPath')
                ->once()
                ->with($theme)
                ->andReturn('/path/to/customization.json');

            $this->filesystem->shouldReceive('write')
                ->never();

            $theme->shouldReceive('refineCustomizationWithDefaults')
                ->once()
                ->with($data)
                ->andReturn($data);

            expect(
                fn () =>
                $this->accessor->save($theme, $data),
            )->toThrow(ThemeSaveException::class);
        });

        it('should throw an exception if the file is not writable', function () {
            $theme = m::mock(Theme::class);
            $data = ['color' => 'blue'];
            $json = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);

            $this->helper->shouldReceive('getCustomizationDataPath')
                ->once()
                ->with($theme)
                ->andReturn('/path/to/customization.json');

            $this->filesystem->shouldReceive('write')
                ->once()
                ->with('/path/to/customization.json', $json)
                ->andReturn(false);

            $theme->shouldReceive('refineCustomizationWithDefaults')
                ->once()
                ->with($data)
                ->andReturn($data);

            expect(
                fn () =>
                $this->accessor->save($theme, $data),
            )->toThrow(ThemeSaveException::class);
        });

        it('should load the customization data from the filesystem', function () {
            $theme = m::mock(Theme::class);
            $data = ['color' => 'blue'];
            $json = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
            $path = '/path/to/customization.json';

            $this->helper->shouldReceive('getCustomizationDataPath')
                ->once()
                ->with($theme)
                ->andReturn($path);

            $this->filesystem->shouldReceive('exists')
                ->once()
                ->with($path)
                ->andReturn(true);

            $this->filesystem->shouldReceive('read')
                ->once()
                ->with($path)
                ->andReturn($json);

            $theme->shouldReceive('refineCustomizationWithDefaults')
                ->once()
                ->with($data)
                ->andReturn($data);

            $result = $this->accessor->load($theme);

            expect($result)->toBe($data);
        });

        it('should load an empty array if the file is not found', function () {
            $theme = m::mock(Theme::class);
            $path = '/path/to/customization.json';

            $this->helper->shouldReceive('getCustomizationDataPath')
                ->once()
                ->with($theme)
                ->andReturn($path);

            $this->filesystem->shouldReceive('exists')
                ->once()
                ->with($path)
                ->andReturn(false);

            $theme->shouldReceive('refineCustomizationWithDefaults')
                ->once()
                ->andReturn([]);

            $result = $this->accessor->load($theme);

            expect($result)->toBe([]);
        });

        it('should throw an exception if decoding the data fails', function () {
            $theme = m::mock(Theme::class);
            $path = '/path/to/customization.json';

            $this->helper->shouldReceive('getCustomizationDataPath')
                ->once()
                ->with($theme)
                ->andReturn($path);

            $this->filesystem->shouldReceive('exists')
                ->once()
                ->with($path)
                ->andReturn(true);

            $this->filesystem->shouldReceive('read')
                ->once()
                ->with($path)
                ->andReturn('{"color":"blue"');

            expect(function () use ($theme) {
                $this->accessor->load($theme);
            })->toThrow(ThemeLoadException::class);
        });
    }
)->group('DefaultThemeCustomizationAccessor', 'schema');
