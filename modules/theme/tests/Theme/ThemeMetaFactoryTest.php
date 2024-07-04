<?php

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\ThemeSchema;
use CmsTool\Theme\Schema\ThemeSchemaFactory;
use CmsTool\Theme\ThemeAuthor;
use CmsTool\Theme\ThemeMeta;
use CmsTool\Theme\ThemeMetaFactory;
use CmsTool\Theme\ThemeName;
use Mockery as m;

describe(
    'ThemeMetaFactory',
    function () {
        beforeEach(function () {
            $this->schemaFactory = m::mock(ThemeSchemaFactory::class);
            $this->factory = new ThemeMetaFactory($this->schemaFactory);
        });

        it('creates a theme meta object from data', function () {
            $data = [
                'uid' => 'theme-uid',
                'name' => 'Theme Name',
                'version' => '1.0.0',
                'images' => ['image1.jpg', 'image2.jpg'],
                'tags' => ['tag1', 'tag2'],
                'link' => 'https://example.com/theme',
                'preset' => 'default',
                'author' => [
                    'name' => 'Author Name',
                    'link' => 'https://example.com/author',
                ],
                'readonly' => true,
                'extension' => ['key' => 'value'],
                'schema' => [],
            ];

            $expectedMeta = new ThemeMeta(
                uid: 'theme-uid',
                name: new ThemeName('Theme Name'),
                version: '1.0.0',
                images: ['image1.jpg', 'image2.jpg'],
                tags: ['tag1', 'tag2'],
                link: 'https://example.com/theme',
                preset: 'default',
                author: new ThemeAuthor('Author Name', 'https://example.com/author'),
                readonly: true,
                extension: ['key' => 'value'],
                schema: new ThemeSchema(),
            );

            $this->schemaFactory->shouldReceive('create')->andReturn(new ThemeSchema());

            $result = $this->factory->create($data);

            expect($result)->toEqual($expectedMeta);
        });

        it('throws an exception when required keys are missing', function () {
            $data = [
                'uid' => 'theme-uid',
                'name' => 'Theme Name',
                'version' => '1.0.0',
                'images' => ['image1.jpg', 'image2.jpg'],
                'tags' => ['tag1', 'tag2'],
                'link' => 'https://example.com/theme',
                'preset' => 'default',
                'author' => [
                    'link' => 'https://example.com/author',
                ],
                'readonly' => true,
                'extension' => ['key' => 'value'],
                'schema' => [],
            ];

            $this->schemaFactory->shouldReceive('create')->andReturn(new ThemeSchema());

            expect(fn () => $this->factory->create($data))->toThrow(ArrayKeyMissingException::class);
        });

        it('creates an author object from a string', function () {
            $data = [
                'uid' => 'theme-uid',
                'name' => 'Theme Name',
                'version' => '1.0.0',
                'images' => ['image1.jpg', 'image2.jpg'],
                'tags' => ['tag1', 'tag2'],
                'link' => 'https://example.com/theme',
                'preset' => 'default',
                'author' => 'Author Name',
                'readonly' => true,
                'extension' => ['key' => 'value'],
                'schema' => [],
            ];

            $this->schemaFactory->shouldReceive('create')->andReturn(new ThemeSchema());

            $result = $this->factory->create($data);

            $expectedAuthor = new ThemeAuthor($data['author']);

            expect($result->author)->toEqual($expectedAuthor);
        });
    }
)->group('ThemeMetaFactory', 'theme');
