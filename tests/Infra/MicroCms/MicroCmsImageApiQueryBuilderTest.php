<?php

use Takemo101\CmsTool\Infra\MicroCms\MicroCmsImageApiQueryBuilder;

describe(
    'MicroCmsImageApiQueryBuilder',
    function () {

        it('should return null when the URL is empty', function () {
            $builder = new MicroCmsImageApiQueryBuilder();

            $result = $builder->build(null);

            expect($result)->toBeNull();
        });

        it('should build the query string with the provided parameters', function () {
            $builder = new MicroCmsImageApiQueryBuilder();

            $url = 'https://example.com/image.jpg';
            $params = [
                'w' => 500,
                'h' => 300,
                'q' => 80,
                'fm' => 'jpg',
                'dpr' => 2,
            ];

            $result = $builder->build($url, $params);

            expect($result)->toBe('https://example.com/image.jpg?w=500&h=300&q=80&fm=jpg&dpr=2');
        });

        it('should build the query string with default values for missing parameters', function () {
            $builder = new MicroCmsImageApiQueryBuilder();

            $url = 'https://example.com/image.jpg';
            $params = [
                'w' => 500,
                'q' => 80,
            ];

            $result = $builder->build($url, $params);

            expect($result)->toBe('https://example.com/image.jpg?w=500&q=80');
        });

        it('should return null when the URL is empty even with parameters', function () {
            $builder = new MicroCmsImageApiQueryBuilder();

            $params = [
                'w' => 500,
                'h' => 300,
            ];

            $result = $builder->build(null, $params);

            expect($result)->toBeNull();
        });

        it('should return null when no parameters are provided', function () {
            $builder = new MicroCmsImageApiQueryBuilder();

            $url = 'https://example.com/image.jpg';

            $result = $builder->build($url);

            expect($result)->toBe('https://example.com/image.jpg');
        });

        it('should return null when the URL is empty and no parameters are provided', function () {
            $builder = new MicroCmsImageApiQueryBuilder();

            $result = $builder->build(null);

            expect($result)->toBeNull();
        });

        it('should build the query string with custom parameter names', function () {
            $builder = new MicroCmsImageApiQueryBuilder();

            $url = 'https://example.com/image.jpg';
            $params = [
                'width' => 500,
                'height' => 300,
                'quality' => 80,
                'format' => 'jpg',
                'dpr' => 2,
            ];

            $result = $builder->build($url, $params);

            expect($result)->toBe('https://example.com/image.jpg?w=500&h=300&q=80&fm=jpg&dpr=2');
        });
    }
)->group('MicroCmsImageApiQueryBuilder', 'infra');
