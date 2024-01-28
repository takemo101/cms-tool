<?php

use CmsTool\Theme\Support\ThemeReadmeReader;
use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemePathHelper;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Filesystem\PathHelper;
use Mockery as m;

beforeEach(function () {
    $this->filesystem = m::mock(LocalFilesystem::class);
    $this->path = m::mock(ThemePathHelper::class);

    $this->reader = new ThemeReadmeReader(
        $this->filesystem,
        $this->path,
        new PathHelper(),
    );
});

describe(
    'ThemeReadmeReader',
    function () {

        it('returns the contents of the Readme file if it exists', function () {
            $theme = m::mock(Theme::class);
            $pattern = 'theme/path/*';
            $paths = ['theme/path/readme.md'];
            $expectedContents = 'This is the Readme file';

            $this->path->shouldReceive('getThemePath')
                ->once()
                ->with($theme, '*')
                ->andReturn($pattern);
            $this->filesystem->shouldReceive('glob')
                ->once()
                ->with($pattern)
                ->andReturn($paths);
            $this->filesystem->shouldReceive('read')
                ->once()
                ->with('theme/path/readme.md')
                ->andReturn($expectedContents);

            $result = $this->reader->get($theme);

            expect($result)->toBe($expectedContents);
        });

        it('returns null if the Readme file does not exist', function () {
            $theme = m::mock(Theme::class);
            $pattern = 'theme/path/*';
            $paths = ['theme/path/somefile.txt'];

            $this->path->shouldReceive('getThemePath')
                ->once()
                ->with($theme, '*')
                ->andReturn($pattern);
            $this->filesystem->shouldReceive('glob')
                ->once()
                ->with($pattern)
                ->andReturn($paths);

            $result = $this->reader->get($theme);

            expect($result)->toBeNull();
        });
    }
)->group('support', 'ThemeReadmeReader');
