<?php

use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemeAuthor;
use CmsTool\Theme\ThemeConfig;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemePathHelper;
use CmsTool\Theme\ThemeMeta;
use CmsTool\Theme\ThemeName;
use Takemo101\Chubby\Filesystem\PathHelper;
use Mockery as m;
use Takemo101\Chubby\Filesystem\LocalFilesystem;

beforeEach(function () {
    $this->theme = new Theme(
        id: new ThemeId('theme-id'),
        directory: '/path/to/theme',
        active: true,
        meta: new ThemeMeta(
            uid: 'theme-id',
            name: new ThemeName('Theme Name'),
            version: '1.0',
            images: [],
            tags: [],
            link: null,
            preset: null,
            author: new ThemeAuthor('Author Name'),
        ),
    );
    $this->helper = new ThemePathHelper(new PathHelper());
    $this->filesystem = m::mock(LocalFilesystem::class);
});

describe(
    'ThemePathHelper',
    function () {

        it('should return the correct theme path', function () {
            $actual = $this->helper->getThemePath($this->theme, 'path1', 'path2');

            $expected = $this->theme->directory . '/path1/path2';

            expect($actual)->toBe($expected);
        })->skipOnWindows();

        it('should return the correct theme setting path', function () {
            $actual = $this->helper->getThemeSettingPath($this->theme);

            $expected = $this->theme->directory . '/' . ThemeConfig::MetaFilename;

            expect($actual)->toBe($expected);
        })->skipOnWindows();

        it('should return the correct theme customization path', function () {
            $actual = $this->helper->getCustomizationDataPath($this->theme);

            $expected = $this->theme->directory . '/' . ThemeConfig::CustomizationDataFilename;

            expect($actual)->toBe($expected);
        })->skipOnWindows();

        it('should return the correct asset path', function () {
            $actual = $this->helper->getAssetPath($this->theme, 'path1', 'path2');

            $expected = $this->theme->directory . '/' . ThemeConfig::AssetsPath . '/path1/path2';

            expect($actual)->toBe($expected);
        })->skipOnWindows();

        it('should return the correct template path', function () {
            $actual = $this->helper->getTemplatePath($this->theme, 'path1', 'path2');

            $expected = $this->theme->directory . '/' . ThemeConfig::TemplatesPath . '/path1/path2';

            expect($actual)->toBe($expected);
        })->skipOnWindows();

        it('should call PathHelper method', function (string $method) {
            $actual = $this->helper->{$method}('/path/to/file.txt');

            expect($actual)->not->toBeEmpty();
        })->with([
            'dirname',
            'basename',
            'extension',
        ]);

        it('should skip creating a temporary directory if the theme is readonly', function () {

            $theme = new Theme(
                id: new ThemeId('theme-id'),
                directory: '/path/to/theme',
                active: true,
                meta: new ThemeMeta(
                    uid: 'theme-id',
                    name: new ThemeName('Theme Name'),
                    version: '1.0',
                    images: [],
                    tags: [],
                    link: null,
                    preset: null,
                    author: new ThemeAuthor('Author Name'),
                    readonly: false,
                ),
            );

            $this->filesystem->shouldReceive('exists')
                ->never();

            $this->helper->makeTemporaryDirectoryOrSkip($theme, $this->filesystem);
        })->skipOnWindows();

        it('should create a temporary directory if the theme is readonly', function () {

            $theme = new Theme(
                id: new ThemeId('theme-id'),
                directory: '/path/to/theme',
                active: true,
                meta: new ThemeMeta(
                    uid: 'theme-id',
                    name: new ThemeName('Theme Name'),
                    version: '1.0',
                    images: [],
                    tags: [],
                    link: null,
                    preset: null,
                    author: new ThemeAuthor('Author Name'),
                    readonly: true,
                ),
            );

            $this->filesystem->shouldReceive('exists')
                ->once()
                ->andReturn(false);

            $this->filesystem->shouldReceive('makeDirectory')
                ->once()
                ->andReturn(true);

            $this->helper->makeTemporaryDirectoryOrSkip($theme, $this->filesystem);
        })->skipOnWindows();
    }
)->group('ThemePathHelper', 'theme');
