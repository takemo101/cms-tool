<?php

use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemeConfig;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemePathHelper;
use CmsTool\Theme\ThemeSetting;
use Takemo101\Chubby\Filesystem\PathHelper;

beforeEach(function () {
    $this->theme = new Theme(
        id: new ThemeId('theme-id'),
        directory: '/path/to/theme',
        active: true,
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

            $excepted = $this->theme->directory . '/' . ThemeConfig::SettingFilename;

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
