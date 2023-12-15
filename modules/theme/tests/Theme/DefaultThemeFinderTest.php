<?php

use CmsTool\Theme\DefaultThemeFinder;
use CmsTool\Theme\Exception\NotFoundThemeException;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Filesystem\PathHelper;
use Mockery as m;

describe(
    'DefaultThemeFinder',
    function () {

        it('checks if a theme exists', function () {
            $filesystem = m::mock(LocalFilesystem::class);
            $helper = new PathHelper();
            $locations = ['/path/to/themes'];

            $finder = new DefaultThemeFinder($filesystem, $helper, $locations);

            $id = 'my-theme';

            $filesystem->shouldReceive('exists')
                ->with('/path/to/themes/my-theme/theme.json')
                ->andReturnTrue();

            // Call the exists method and expect it to return true
            expect($finder->exists($id))->toBeTrue();
        });

        it('throws an exception when a theme is not found', function () {
            $filesystem = m::mock(LocalFilesystem::class);
            $helper = new PathHelper();
            $finder = new DefaultThemeFinder($filesystem, $helper);

            $id = 'non-existent-theme';

            $filesystem->shouldReceive('exists')
                ->with('/path/to/themes/non-existent-theme/theme.json')
                ->andReturnFalse();

            // Call the find method and expect it to throw a NotFoundThemeException
            expect(fn () => $finder->find($id))->toThrow(NotFoundThemeException::class);
        });

        it('finds a theme by ID', function () {
            $filesystem = m::mock(LocalFilesystem::class);
            $helper = new PathHelper();
            $locations = ['/path/to/themes'];
            $finder = new DefaultThemeFinder($filesystem, $helper, $locations);

            $id = 'my-theme';

            $filesystem->shouldReceive('exists')
                ->with('/path/to/themes/my-theme/theme.json')
                ->andReturnTrue();

            // Call the find method and expect it to return the theme path
            expect($finder->find($id))->toBe('/path/to/themes/my-theme/theme.json');
        });

        it('finds all themes', function () {
            $filesystem = m::mock(LocalFilesystem::class);
            $helper = new PathHelper();
            $locations = ['/path/to/themes', '/another/path/to/themes'];

            $finder = new DefaultThemeFinder($filesystem, $helper, $locations);

            // Mock the filesystem's glob method to return theme directories
            $filesystem->shouldReceive('glob')
                ->with('/path/to/themes/*')
                ->andReturn(['/path/to/themes/theme1', '/path/to/themes/theme2']);

            $filesystem->shouldReceive('glob')
                ->with('/another/path/to/themes/*')
                ->andReturn(['/another/path/to/themes/theme3']);

            // Mock the filesystem's exists method to return true for theme files
            $filesystem->shouldReceive('exists')
                ->with('/path/to/themes/theme1/theme.json')
                ->andReturnTrue();

            $filesystem->shouldReceive('exists')
                ->with('/path/to/themes/theme2/theme.json')
                ->andReturnTrue();

            $filesystem->shouldReceive('exists')
                ->with('/another/path/to/themes/theme3/theme.json')
                ->andReturnTrue();

            // Call the findAll method and expect it to return an array of theme paths
            expect($finder->findAll())->toBe([
                'theme1' => '/path/to/themes/theme1/theme.json',
                'theme2' => '/path/to/themes/theme2/theme.json',
                'theme3' => '/another/path/to/themes/theme3/theme.json',
            ]);
        });

        it('adds a new location', function () {
            $filesystem = m::mock(LocalFilesystem::class);
            $helper = new PathHelper();
            $finder = new DefaultThemeFinder($filesystem, $helper);

            $location = '/new/path/to/themes';

            // Mock the filesystem's realpath method to return the location
            $filesystem->shouldReceive('realpath')
                ->with($location)
                ->andReturn($location);

            // Call the addLocation method
            $finder->addLocation($location);

            // Expect the locations array to contain the new location
            expect($finder->getLocations())->toContain($location);
        });
    },
)->group('DefaultThemeFinder', 'theme');
