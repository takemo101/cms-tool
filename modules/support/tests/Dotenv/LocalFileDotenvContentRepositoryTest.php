<?php

use CmsTool\Support\Dotenv\DotenvContent;
use CmsTool\Support\Dotenv\LocalFileDotenvContentRepository;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Support\ApplicationPath;
use Takemo101\Chubby\Support\ApplicationSummary;
use Takemo101\Chubby\Http\Uri\ApplicationUri;
use Mockery as m;

describe(
    'LocalFileDotenvContentRepository',
    function () {
        it('finds the dotenv content if the file exists', function () {
            $path = new ApplicationPath(
                basePath: '/path/to',
            );
            $summary = new ApplicationSummary(
                uri: m::mock(ApplicationUri::class),
                env: 'testing',
            );
            $filesystem = m::mock(LocalFilesystem::class);

            $repository = new LocalFileDotenvContentRepository($path, $summary, $filesystem);

            $content = new DotenvContent('KEY=value');

            $filesystem->shouldReceive('exists')->with('/path/to/.env')->andReturn(false);
            $filesystem->shouldReceive('exists')->with('/path/to/.env.testing')->andReturn(true);
            $filesystem->shouldReceive('read')->with('/path/to/.env.testing')->andReturn('KEY=value');

            $result = $repository->find();

            expect($result)->toBeInstanceOf(DotenvContent::class);
            expect($result->value())->toBe('KEY=value');
        });

        it('returns null if the dotenv file does not exist', function () {
            $path = new ApplicationPath(
                basePath: '/path/to',
            );
            $summary = new ApplicationSummary(
                uri: m::mock(ApplicationUri::class),
                env: 'testing',
            );
            $filesystem = m::mock(LocalFilesystem::class);

            $repository = new LocalFileDotenvContentRepository($path, $summary, $filesystem);

            $filesystem->shouldReceive('exists')->with('/path/to/.env')->andReturn(false);
            $filesystem->shouldReceive('exists')->with('/path/to/.env.testing')->andReturn(false);

            $result = $repository->find();

            expect($result)->toBeNull();
        });

        it('saves the dotenv content to the existing file', function () {
            $path = new ApplicationPath(
                basePath: '/path/to',
            );
            $summary = new ApplicationSummary(
                uri: m::mock(ApplicationUri::class),
                env: 'testing',
            );
            $filesystem = m::mock(LocalFilesystem::class);

            $repository = new LocalFileDotenvContentRepository($path, $summary, $filesystem);

            $content = new DotenvContent('KEY=value');

            $filesystem->shouldReceive('exists')->with('/path/to/.env')->andReturn(false);
            $filesystem->shouldReceive('exists')->with('/path/to/.env.testing')->andReturn(true);
            $filesystem->shouldReceive('write')
                ->with('/path/to/.env.testing', 'KEY=value')
                ->andReturn(true)
                ->once();

            $repository->save($content);
        });

        it('throws an exception when failed to write the dotenv file', function () {
            $path = new ApplicationPath(
                basePath: '/path/to',
            );
            $summary = new ApplicationSummary(
                uri: m::mock(ApplicationUri::class),
                env: 'testing',
            );
            $filesystem = m::mock(LocalFilesystem::class);

            $repository = new LocalFileDotenvContentRepository($path, $summary, $filesystem);

            $content = new DotenvContent('KEY=value');

            $filesystem->shouldReceive('exists')->with('/path/to/.env')->andReturn(false);
            $filesystem->shouldReceive('exists')->with('/path/to/.env.testing')->andReturn(true);
            $filesystem->shouldReceive('write')->with('/path/to/.env.testing', 'KEY=value')->andReturn(false);

            expect(function () use ($repository, $content) {
                $repository->save($content);
            })->toThrow(RuntimeException::class, 'Failed to write the dotenv file: /path/to/.env.testing');
        });
    },
)->group('LocalFileDotenvContentRepository', 'dotenv');
