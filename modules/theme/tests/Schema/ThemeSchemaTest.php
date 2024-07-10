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

        it('should extract the default customization data from the schema settings', function () {
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

            $settings = [$schemaSettings1, $schemaSettings2];
            $themeSchema = new ThemeSchema(...$settings);

            expect($themeSchema->normalizeCustomization([]))->toBe([
                'setting1' => ['key1' => 'default1'],
                'setting2' => ['key2' => 'default2'],
            ]);
        });

        it('should extract the customization data from the schema settings', function () {
            $schemaSettings1 = new SchemaSettings(
                new SchemaSettingId('setting1'),
                'Setting 1',
                new TextSetting(
                    id: new SchemaSettingId('key1'),
                    label: 'Key 1',
                    default: 'value1',
                ),
            );

            $schemaSettings2 = new SchemaSettings(
                new SchemaSettingId('setting2'),
                'Setting 2',
                new TextSetting(
                    id: new SchemaSettingId('key2'),
                    label: 'Key 2',
                    default: 'value2',
                ),
            );

            $settings = [$schemaSettings1, $schemaSettings2];
            $themeSchema = new ThemeSchema(...$settings);

            expect($themeSchema->normalizeCustomization([
                'setting1' => ['key1' => 'value1'],
                'setting2' => ['key2' => 'value2'],
            ]))->toBe([
                'setting1' => ['key1' => 'value1'],
                'setting2' => ['key2' => 'value2'],
            ]);
        });
    }
)->group('ThemeSchema', 'schema');
