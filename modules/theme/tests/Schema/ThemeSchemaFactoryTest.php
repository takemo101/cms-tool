<?php

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\SchemaSettingFactory;
use CmsTool\Theme\Schema\SchemaSettingId;
use CmsTool\Theme\Schema\ThemeSchema;
use CmsTool\Theme\Schema\ThemeSchemaFactory;
use CmsTool\Theme\Schema\SchemaSettings;
use CmsTool\Theme\Schema\Setting\AbstractSetting;
use Mockery as m;

describe(
    'ThemeSchemaFactory',
    function () {
        beforeEach(function () {
            $this->schemaSettingFactory = m::mock(SchemaSettingFactory::class);
            $this->factory = new ThemeSchemaFactory($this->schemaSettingFactory);
        });

        it('creates a theme schema object from schema data', function () {
            $data = [
                [
                    'id' => 'schema1',
                    'title' => 'Schema 1',
                    'settings' => [
                        [
                            'type' => 'checkbox',
                            'label' => 'dummy label',
                            'default' => true,
                        ],
                        [
                            'type' => 'color',
                            'label' => 'dummy label',
                            'default' => '#ffffff',
                        ],
                    ],
                ],
                [
                    'id' => 'schema2',
                    'title' => 'Schema 2',
                    'settings' => [
                        [
                            'type' => 'header',
                            'title' => 'dummy title',
                        ],
                        [
                            'type' => 'number',
                            'label' => 'dummy label',
                            'min' => 0,
                            'max' => 100,
                            'default' => 10,
                        ],
                    ],
                ],
            ];

            $setting = m::mock(AbstractSetting::class);

            $schemaSettings1 = new SchemaSettings(
                new SchemaSettingId('schema1'),
                'Schema 1',
                $setting,
                $setting,
            );

            $schemaSettings2 = new SchemaSettings(
                new SchemaSettingId('schema2'),
                'Schema 2',
                $setting,
                $setting,
            );

            $this->schemaSettingFactory->shouldReceive('create')
                ->andReturn($setting);

            $expectedSchema = new ThemeSchema($schemaSettings1, $schemaSettings2);

            $result = $this->factory->create($data);

            expect($result)->toEqual($expectedSchema);
        });

        it('throws an exception when a required array key is missing', function () {
            $data = [
                [
                    'id' => 'schema1',
                    'settings' => [
                        [
                            'type' => 'checkbox',
                            'label' => 'dummy label',
                            'default' => true,
                        ],
                        [
                            'type' => 'color',
                            'label' => 'dummy label',
                            'default' => '#ffffff',
                        ],
                    ],
                ],
                [
                    'id' => 'schema2',
                    'title' => 'Schema 2',
                    'settings' => [
                        [
                            'type' => 'header',
                            'title' => 'dummy title',
                        ],
                        [
                            'type' => 'number',
                            'label' => 'dummy label',
                            'min' => 0,
                            'max' => 100,
                            'default' => 10,
                        ],
                    ],
                ],
            ];

            expect(fn () => $this->factory->create($data))->toThrow(ArrayKeyMissingException::class);
        });
    }
)->group('ThemeSchemaFactory', 'schema');
