<?php

use CmsTool\Support\JsonAccess\Exception\JsonConversionException;
use CmsTool\Support\JsonAccess\Exception\JsonNotAccessibleException;
use CmsTool\Support\JsonAccess\Exception\NotFoundJsonException;
use CmsTool\Support\JsonAccess\DefaultJsonAccessor;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Tests\TestCase;

beforeEach(
    function () {
        $this->filesystem = Mockery::mock(LocalFilesystem::class);
        $this->accessor = new DefaultJsonAccessor($this->filesystem);
    }
);

describe(
    'default-json-accessor::load',
    function () {

        test(
            'should throw NotFoundJsonException if file does not exist',
            function () {
                $path = '/path/to/test.json';

                $this->filesystem->shouldReceive('exists')
                    ->once()
                    ->with($path)
                    ->andReturn(false);

                expect(fn () => $this->accessor->load($path))->toThrow(NotFoundJsonException::class);
            }
        );

        test(
            'should throw NotFoundJsonException if path is not a file',
            function () {
                $path = '/path/to/directory';

                $this->filesystem->shouldReceive('exists')
                    ->once()
                    ->with($path)
                    ->andReturn(true);

                $this->filesystem->shouldReceive('isFile')
                    ->once()
                    ->with($path)
                    ->andReturn(false);

                expect(fn () => $this->accessor->load($path))->toThrow(NotFoundJsonException::class);
            }
        );

        test(
            'should throw JsonNotAccessibleException if file is not readable',
            function () {
                $path = '/path/to/test.json';

                $this->filesystem->shouldReceive('exists')
                    ->once()
                    ->with($path)
                    ->andReturn(true);

                $this->filesystem->shouldReceive('isFile')
                    ->once()
                    ->with($path)
                    ->andReturn(true);

                $this->filesystem->shouldReceive('isReadable')
                    ->once()
                    ->with($path)
                    ->andReturn(false);

                expect(fn () => $this->accessor->load($path))->toThrow(JsonNotAccessibleException::class);
            }
        );

        test(
            'should throw JsonConversionException if file contains invalid JSON',
            function () {
                $path = '/path/to/test.json';

                $this->filesystem->shouldReceive('exists')
                    ->once()
                    ->with($path)
                    ->andReturn(true);

                $this->filesystem->shouldReceive('isFile')
                    ->once()
                    ->with($path)
                    ->andReturn(true);

                $this->filesystem->shouldReceive('isReadable')
                    ->once()
                    ->with($path)
                    ->andReturn(true);

                $this->filesystem->shouldReceive('read')
                    ->once()
                    ->with($path)
                    ->andReturn('{"foo": "bar",}');

                expect(fn () => $this->accessor->load($path))->toThrow(JsonConversionException::class);
            }
        );

        test(
            'should return array if file contains valid JSON',
            function () {
                $path = '/path/to/test.json';
                $data = ['foo' => 'bar'];

                $this->filesystem->shouldReceive('exists')
                    ->once()
                    ->with($path)
                    ->andReturn(true);

                $this->filesystem->shouldReceive('isFile')
                    ->once()
                    ->with($path)
                    ->andReturn(true);

                $this->filesystem->shouldReceive('isReadable')
                    ->once()
                    ->with($path)
                    ->andReturn(true);

                $this->filesystem->shouldReceive('read')
                    ->once()
                    ->with($path)
                    ->andReturn(json_encode($data));

                $result = $this->accessor->load($path);

                expect($result)->toBe($data);
            }
        );

        test(
            'Read the JSON file for the test',
            function () {
                /** @var TestCase $this */

                /** @var LocalFilesystem */
                $filesystem = $this->getContainer()->get(LocalFilesystem::class);

                $accessor = new DefaultJsonAccessor($filesystem);

                $path = dirname(__DIR__, 1) . '/resources/json/test.json';

                $actual = $accessor->load($path);

                expect($actual)->toBeArray();
            }
        );
    }
)->group('local-json-accessor', 'json-access');

describe(
    'local-json-accessor::save',
    function () {

        test(
            'should throw JsonConversionException if data cannot be encoded as JSON',
            function () {
                $path = '/path/to/test.json';
                $data = ['foo' => "\xB1\x31"];

                expect(fn () => $this->accessor->save($path, $data))->toThrow(JsonConversionException::class);
            }
        );

        test(
            'should throw JsonNotAccessibleException if file is not writable',
            function () {
                $path = '/path/to/test.json';
                $data = ['foo' => 'bar'];

                $this->filesystem->shouldReceive('write')
                    ->once()
                    ->with($path, json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT))
                    ->andReturn(false);

                expect(fn () => $this->accessor->save($path, $data))->toThrow(JsonNotAccessibleException::class);
            }
        );

        test(
            'should write data to file if file is writable',
            function () {
                $path = '/path/to/test.json';
                $data = ['foo' => 'bar'];

                $this->filesystem->shouldReceive('write')
                    ->once()
                    ->with($path, json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT))
                    ->andReturn(true);

                $this->accessor->save($path, $data);
            }
        );

        test(
            'Save the temporary file and read it',
            function () {
                /** @var TestCase $this */

                /** @var LocalFilesystem */
                $filesystem = $this->getContainer()->get(LocalFilesystem::class);

                $accessor = new DefaultJsonAccessor($filesystem);

                $path = dirname(__DIR__, 1) . '/resources/json/temp.json';

                $excepted = ['foo' => 'bar'];

                $accessor->save($path, $excepted);

                $actual = $accessor->load($path);

                expect($actual)->toBe($excepted);

                $filesystem->delete($path);
            }
        );
    }
)->group('json-accessor', 'json-access');
