<?php

use CmsTool\Theme\Schema\ThemeCustomizationPreviewer;
use CmsTool\Theme\ThemeId;

describe(
    'ThemeCustomizationPreviewer',
    function () {
        beforeEach(function () {
            $this->themeId = new ThemeId('my-theme');
            $this->previewer = new ThemeCustomizationPreviewer();
        });

        it('sets and gets the preview data for a theme id', function () {
            $preview = [
                'header' => [
                    'background_color' => '#ffffff',
                    'text_color' => '#000000',
                ],
                'footer' => [
                    'background_color' => '#f0f0f0',
                    'text_color' => '#333333',
                ],
            ];

            $this->previewer->set($this->themeId, $preview);

            expect($this->previewer->get($this->themeId))->toBe($preview);
        });

        it('returns false when getting the preview data for a non-existent theme id', function () {
            expect($this->previewer->get($this->themeId))->toBeFalse();
        });
    }
)->group('ThemeCustomizationPreviewer', 'schema');
