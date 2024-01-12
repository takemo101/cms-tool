<?php

use CmsTool\Support\Translation\TranslationJsonFileAccessor;
use CmsTool\Support\Translation\Exception\NotFoundTranslationException;
use CmsTool\Support\Translation\Exception\TranslationConversionException;
use CmsTool\Support\JsonAccess\JsonArrayAccessor;
use Takemo101\Chubby\Filesystem\PathHelper;
use Mockery as m;

describe(
    'TranslationJsonFileAccessor',
    function () {

        it('should load translation data correctly', function () {
            $jsonAccessor = m::mock(JsonArrayAccessor::class);
            $jsonAccessor
                ->shouldReceive('exists')
                ->with('/path/to/translations/domain.en.json')
                ->andReturn(true);
            $jsonAccessor
                ->shouldReceive('load')
                ->with('/path/to/translations/domain.en.json')
                ->andReturn(['key' => 'value']);

            $translationAccessor = new TranslationJsonFileAccessor(
                new PathHelper(),
                $jsonAccessor,
                ['/path/to/translations']
            );

            $actual = $translationAccessor->load('domain', 'en');

            // Call the load method and assert the returned data
            expect($actual)->toBe(['key' => 'value']);
        });

        it('should throw an exception when translation file is not found', function () {
            $jsonAccessor = m::mock(JsonArrayAccessor::class);
            $jsonAccessor
                ->shouldReceive('exists')
                ->with('/path/to/translations/domain.en.json')
                ->andReturn(false);

            $translationAccessor = new TranslationJsonFileAccessor(
                new PathHelper(),
                $jsonAccessor,
                ['/path/to/translations']
            );

            // Call the load method and assert that it throws the expected exception
            expect(fn () => $translationAccessor->load('domain', 'en'))
                ->toThrow(NotFoundTranslationException::class);
        });

        it('should throw an exception when translation data cannot be converted', function () {
            $jsonAccessor = m::mock(JsonArrayAccessor::class);
            $jsonAccessor
                ->shouldReceive('exists')
                ->with('/path/to/translations/domain.en.json')
                ->andReturn(true);
            $jsonAccessor
                ->shouldReceive('load')
                ->with('/path/to/translations/domain.en.json')
                ->andThrow(new TranslationConversionException('domain', 'en'));

            $translationAccessor = new TranslationJsonFileAccessor(
                new PathHelper(),
                $jsonAccessor,
                ['/path/to/translations']
            );

            // Call the load method and assert that it throws the expected exception
            expect(fn () => $translationAccessor->load('domain', 'en'))
                ->toThrow(TranslationConversionException::class);
        });

        it('should check if translation file exists', function () {
            $jsonAccessor = m::mock(JsonArrayAccessor::class);
            $jsonAccessor
                ->shouldReceive('exists')
                ->with('/path/to/translations/domain.en.json')
                ->andReturn(true);

            $translationAccessor = new TranslationJsonFileAccessor(
                new PathHelper(),
                $jsonAccessor,
                ['/path/to/translations']
            );

            // Call the exists method and assert the returned value
            $actual = $translationAccessor->exists('domain', 'en');
            expect($actual)->toBeTrue();
        });

        it('should return false when translation file does not exist', function () {
            $jsonAccessor = m::mock(JsonArrayAccessor::class);
            $jsonAccessor
                ->shouldReceive('exists')
                ->with('/path/to/translations/domain.en.json')
                ->andReturn(false);

            $translationAccessor = new TranslationJsonFileAccessor(
                new PathHelper(),
                $jsonAccessor,
                ['/path/to/translations']
            );

            // Call the exists method and assert the returned value
            $actual = $translationAccessor->exists('domain', 'en');
            expect($actual)->toBeFalse();
        });

        it('should add a translation resource', function () {
            $jsonAccessor = m::mock(JsonArrayAccessor::class);

            $translationAccessor = new TranslationJsonFileAccessor(
                new PathHelper(),
                $jsonAccessor,
                ['/path/to/translations']
            );

            // Call the addResource method
            $translationAccessor->addResource('/path/to/translations');

            // Assert that the resource has been added to the locations array
            expect($translationAccessor->getLocations())->toContain('/path/to/translations');
        });

        it('should save translation data correctly', function () {

            $jsonAccessor = m::mock(JsonArrayAccessor::class);
            $jsonAccessor
                ->shouldReceive('save')
                ->with('/path/to/translations/domain.en.json', ['key' => 'value'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            // Create an instance of the TranslationJsonFileAccessor class
            $translationAccessor = new TranslationJsonFileAccessor(
                new PathHelper(),
                $jsonAccessor,
                ['/path/to/translations']
            );

            // Call the save method
            expect(fn () => $translationAccessor->save('domain', 'en', ['key' => 'value']))
                ->not->toThrow(TranslationConversionException::class);

            // No assertion needed, if the save method does not throw an exception, it is considered successful
        });
    }
)->group('TranslationJsonFileAccessor', 'translation');
