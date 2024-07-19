<?php

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\SchemaSettingFactory;
use CmsTool\Theme\Schema\Setting\CheckboxSetting;
use CmsTool\Theme\Schema\Setting\ColorSetting;
use CmsTool\Theme\Schema\Setting\HeaderSetting;
use CmsTool\Theme\Schema\Setting\NumberSetting;
use CmsTool\Theme\Schema\Setting\SelectSetting;
use CmsTool\Theme\Schema\Setting\TextareaSetting;
use CmsTool\Theme\Schema\Setting\TextSetting;

describe(
    'SchemaSettingFactory',
    function () {
        it('creates a CheckboxSetting', function () {
            $factory = SchemaSettingFactory::build();
            $data = [
                'id' => 'dummy',
                'type' => 'checkbox',
                'label' => 'Enable feature',
                'default' => true,
            ];

            $setting = $factory->create($data);

            expect($setting)->toBeInstanceOf(CheckboxSetting::class);
        });

        it('creates a ColorSetting', function () {
            $factory = SchemaSettingFactory::build();
            $data = [
                'id' => 'dummy',
                'type' => 'color',
                'label' => 'Background color',
                'default' => '#ffffff',
            ];

            $setting = $factory->create($data);

            expect($setting)->toBeInstanceOf(ColorSetting::class);
        });

        it('creates a HeaderSetting', function () {
            $factory = SchemaSettingFactory::build();
            $data = [
                'id' => 'dummy',
                'type' => 'header',
                'title' => 'Section title',
            ];

            $setting = $factory->create($data);

            expect($setting)->toBeInstanceOf(HeaderSetting::class);
        });

        it('creates a NumberSetting', function () {
            $factory = SchemaSettingFactory::build();
            $data = [
                'id' => 'dummy',
                'type' => 'number',
                'label' => 'Quantity',
                'default' => 10,
                'min' => 0,
                'max' => 100,
            ];

            $setting = $factory->create($data);

            expect($setting)->toBeInstanceOf(NumberSetting::class);
        });

        it('creates a SelectSetting', function (array $options) {
            $factory = SchemaSettingFactory::build();
            $data = [
                'id' => 'dummy',
                'type' => 'select',
                'label' => 'Size',
                'default' => 'medium',
                'options' => $options,
            ];

            $setting = $factory->create($data);

            expect($setting)->toBeInstanceOf(SelectSetting::class);
        })->with([
            fn () => ['small', 'medium', 'large'],
            fn () => [
                ['value' => 's', 'label' => 'Small'],
                ['value' => 'm', 'label' => 'Medium'],
                ['value' => 'l', 'label' => 'Large'],
            ],
        ]);

        it('creates a TextareaSetting', function () {
            $factory = SchemaSettingFactory::build();
            $data = [
                'id' => 'dummy',
                'type' => 'textarea',
                'label' => 'Description',
                'default' => 'Lorem ipsum dolor sit amet',
            ];

            $setting = $factory->create($data);

            expect($setting)->toBeInstanceOf(TextareaSetting::class);
        });

        it('creates a TextSetting', function () {
            $factory = SchemaSettingFactory::build();
            $data = [
                'id' => 'dummy',
                'type' => 'text',
                'label' => 'Name',
                'default' => 'John Doe',
            ];

            $setting = $factory->create($data);

            expect($setting)->toBeInstanceOf(TextSetting::class);
        });

        it('throws an exception for missing required array key', function () {
            $factory = SchemaSettingFactory::build();

            expect(fn () => $factory->create([
                'typo' => 'undefined',
            ]))->toThrow(ArrayKeyMissingException::class);
        });

        it('throws an exception for undefined type', function () {
            $factory = SchemaSettingFactory::build();

            expect(fn () => $factory->create([
                'type' => 'unknown',
            ]))->toThrow(\InvalidArgumentException::class);
        });
    }
)->group('SchemaSettingFactory', 'schema');
