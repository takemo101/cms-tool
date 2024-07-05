<?php

use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemeAuthor;
use CmsTool\Theme\ThemeConfig;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemePathHelper;
use CmsTool\Theme\ThemeMeta;
use CmsTool\Theme\ThemeName;
use Takemo101\Chubby\Filesystem\PathHelper;

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
            author: new ThemeAuthor('Author Name')
        ),
    );
    $this->helper = new ThemePathHelper(new PathHelper());
});

describe(
    'ThemePathHelper',
    function () {

        it('should return the correct theme path', function () {
            $actual = $this->helper->getThemePath($this->theme, 'path1', 'path2');

            $excepted = $this->theme->directory . '/path1/path2';

            expect($actual)->toBe($excepted);
        })->skipOnWindows();

        it('should return the correct theme setting path', function () {
            $actual = $this->helper->getThemeSettingPath($this->theme);

            $excepted = $this->theme->directory . '/' . ThemeConfig::MetaFilename;

            expect($actual)->toBe($excepted);
        })->skipOnWindows();

        it('should return the correct asset path', function () {
            $actual = $this->helper->getAssetPath($this->theme, 'path1', 'path2');

            $excepted = $this->theme->directory . '/' . ThemeConfig::AssetsPath . '/path1/path2';

            expect($actual)->toBe($excepted);
        })->skipOnWindows();

        it('should return the correct template path', function () {
            $actual = $this->helper->getTemplatePath($this->theme, 'path1', 'path2');

            $excepted = $this->theme->directory . '/' . ThemeConfig::TemplatesPath . '/path1/path2';

            expect($actual)->toBe($excepted);
        })->skipOnWindows();
    }
)->group('ThemePathHelper', 'theme');
