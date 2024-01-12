<?php

use CmsTool\Support\Translation\TranslationStringReplacer;

describe(
    'TranslationStringReplacer',
    function () {

        it('should replace the translation string correctly', function () {
            $replacer = new TranslationStringReplacer();

            $translation = 'Hello :name, welcome to :app!';
            $replace = [
                'name' => 'John',
                'app' => 'MicroCMS'
            ];

            $expected = 'Hello John, welcome to MicroCMS!';
            $actual = $replacer->replace($translation, $replace);

            expect($actual)->toBe($expected);
        });

        it('should replace the translation string with empty values', function () {
            $replacer = new TranslationStringReplacer();

            $translation = 'Hello :name, welcome to :app!';
            $replace = [
                'name' => '',
                'app' => ''
            ];

            $expected = 'Hello , welcome to !';
            $actual = $replacer->replace($translation, $replace);

            expect($actual)->toBe($expected);
        });

        it('should replace the translation string with special characters', function () {
            $replacer = new TranslationStringReplacer();

            $translation = 'Hello :name, welcome to :app!';
            $replace = [
                'name' => '<strong>John</strong>',
                'app' => 'MicroCMS & Co.'
            ];

            $expected = 'Hello <strong>John</strong>, welcome to MicroCMS & Co.!';
            $actual = $replacer->replace($translation, $replace);

            expect($actual)->toBe($expected);
        });
    }
)->group('TranslationStringReplacer', 'translation');
