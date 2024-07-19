<?php

use CmsTool\Theme\Schema\SchemaSettingId;
use CmsTool\Theme\Schema\ThemeSchema;
use CmsTool\Theme\Schema\SchemaSettings;
use CmsTool\Theme\Schema\Setting\TextSetting;


describe(
    'ThemeSchema',
    function () {
        it('should check if the schema settings are empty', function () {
            $settings = [];
            $themeSchema = new ThemeSchema(...$settings);
            expect($themeSchema->isEmpty())->toBeTrue();
        });

        it('should refine the theme\'s customization data with the default values of the schema settings', function () {
            $schemaSettings1 = new SchemaSettings(
                new SchemaSettingId('setting1'),
                'Setting 1',
                new TextSetting(
                    id: new SchemaSettingId('key1'),
                    label: 'Key 1',
                    default: 'default1',
                ),
            );

            $schemaSettings2 = new SchemaSettings(
                new SchemaSettingId('setting2'),
                'Setting 2',
                new TextSetting(
                    id: new SchemaSettingId('key2'),
                    label: 'Key 2',
                    default: 'default2',
                ),
            );

            $themeSchema = new ThemeSchema($schemaSettings1, $schemaSettings2);

            $customizationData = [];
            $expectedResult = [
                'setting1' => ['key1' => 'default1'],
                'setting2' => ['key2' => 'default2'],
            ];

            expect($themeSchema->refineCustomizationWithDefaults($customizationData))->toBe($expectedResult);
        });

        it('should refine the theme\'s customization data with the not set values of the schema settings', function () {
            $schemaSettings1 = new SchemaSettings(
                new SchemaSettingId('setting1'),
                'Setting 1',
                new TextSetting(
                    id: new SchemaSettingId('key1'),
                    label: 'Key 1',
                    default: 'default1',
                ),
            );

            $schemaSettings2 = new SchemaSettings(
                new SchemaSettingId('setting2'),
                'Setting 2',
                new TextSetting(
                    id: new SchemaSettingId('key2'),
                    label: 'Key 2',
                    default: 'default2',
                ),
            );

            $themeSchema = new ThemeSchema($schemaSettings1, $schemaSettings2);

            $customizationData = [];
            $expectedResult = [
                'setting1' => ['key1' => ''],
                'setting2' => ['key2' => ''],
            ];

            expect($themeSchema->refineCustomizationWithNotSet($customizationData))->toBe($expectedResult);
        });
    }
)->group('ThemeSchema', 'schema');
