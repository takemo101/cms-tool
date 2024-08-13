<?php

use CmsTool\Theme\Contract\ThemeCustomizationLoader;
use CmsTool\Theme\Schema\ThemeCustomizationFetcher;
use CmsTool\Theme\Schema\ThemeCustomizationPreviewer;
use CmsTool\Theme\Theme;
use CmsTool\Theme\ThemeAuthor;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemeMeta;
use CmsTool\Theme\ThemeName;
use Mockery as m;

describe(
    'ThemeCustomizationFetcher',
    function () {
        beforeEach(function () {
            $this->loader = m::mock(ThemeCustomizationLoader::class);
            $this->previewer = m::mock(ThemeCustomizationPreviewer::class);
            $this->theme = new Theme(
                id: new ThemeId('my-theme'),
                directory: '/path/to/themes/my-theme',
                meta: new ThemeMeta(
                    uid: 'uid',
                    name: new ThemeName('My Theme'),
                    version: '1.0',
                    images: [],
                    tags: [],
                    link: null,
                    preset: null,
                    author: new ThemeAuthor('name'),
                ),
            );
            $this->fetcher = new ThemeCustomizationFetcher($this->loader, $this->previewer);
        });

        it('returns preview data if available', function () {
            $previewData = [
                'color' => [
                    'primary' => '#ffffff',
                    'secondary' => '#000000',
                ],
                'font' => [
                    'family' => 'Arial',
                    'size' => 14,
                ],
            ];

            $this->previewer->shouldReceive('get')
                ->with($this->theme->id)
                ->andReturn($previewData);

            $this->loader->shouldNotReceive('load');

            $result = $this->fetcher->get($this->theme);

            expect($result)->toBe($previewData);
        });

        it('returns loaded data if preview data is not available', function () {
            $loadedData = [
                'color' => [
                    'primary' => '#000000',
                    'secondary' => '#ffffff',
                ],
                'font' => [
                    'family' => 'Helvetica',
                    'size' => 16,
                ],
            ];

            $this->previewer->shouldReceive('get')
                ->with($this->theme->id)
                ->andReturn(false);

            $this->loader->shouldReceive('load')
                ->with($this->theme)
                ->andReturn($loadedData);

            $result = $this->fetcher->get($this->theme);

            expect($result)->toBe($loadedData);
        });
    }
)->group('ThemeCustomizationFetcher', 'schema');
